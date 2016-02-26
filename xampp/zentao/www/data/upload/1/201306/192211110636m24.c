/********************************************************************************************
 *     LEGAL DISCLAIMER 
 *
 *     (Header of MediaTek Software/Firmware Release or Documentation)
 *
 *     BY OPENING OR USING THIS FILE, BUYER HEREBY UNEQUIVOCALLY ACKNOWLEDGES AND AGREES 
 *     THAT THE SOFTWARE/FIRMWARE AND ITS DOCUMENTATIONS ("MEDIATEK SOFTWARE") RECEIVED 
 *     FROM MEDIATEK AND/OR ITS REPRESENTATIVES ARE PROVIDED TO BUYER ON AN "AS-IS" BASIS 
 *     ONLY. MEDIATEK EXPRESSLY DISCLAIMS ANY AND ALL WARRANTIES, EXPRESS OR IMPLIED, 
 *     INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR 
 *     A PARTICULAR PURPOSE OR NONINFRINGEMENT. NEITHER DOES MEDIATEK PROVIDE ANY WARRANTY 
 *     WHATSOEVER WITH RESPECT TO THE SOFTWARE OF ANY THIRD PARTY WHICH MAY BE USED BY, 
 *     INCORPORATED IN, OR SUPPLIED WITH THE MEDIATEK SOFTWARE, AND BUYER AGREES TO LOOK 
 *     ONLY TO SUCH THIRD PARTY FOR ANY WARRANTY CLAIM RELATING THERETO. MEDIATEK SHALL ALSO
 *     NOT BE RESPONSIBLE FOR ANY MEDIATEK SOFTWARE RELEASES MADE TO BUYER'S SPECIFICATION 
 *     OR TO CONFORM TO A PARTICULAR STANDARD OR OPEN FORUM.
 *     
 *     BUYER'S SOLE AND EXCLUSIVE REMEDY AND MEDIATEK'S ENTIRE AND CUMULATIVE LIABILITY WITH 
 *     RESPECT TO THE MEDIATEK SOFTWARE RELEASED HEREUNDER WILL BE, AT MEDIATEK'S OPTION, 
 *     TO REVISE OR REPLACE THE MEDIATEK SOFTWARE AT ISSUE, OR REFUND ANY SOFTWARE LICENSE 
 *     FEES OR SERVICE CHARGE PAID BY BUYER TO MEDIATEK FOR SUCH MEDIATEK SOFTWARE AT ISSUE. 
 *     
 *     THE TRANSACTION CONTEMPLATED HEREUNDER SHALL BE CONSTRUED IN ACCORDANCE WITH THE LAWS 
 *     OF THE STATE OF CALIFORNIA, USA, EXCLUDING ITS CONFLICT OF LAWS PRINCIPLES.  
 ************************************************************************************************/
#include "x_bim.h"
#include "x_os.h"
#include "x_util.h"
#include "x_assert.h"
#include "x_timer.h"
#include "ir_debug.h"
#include "drv_ir.h"
#include "ir_cus.h"
#include "x_lint.h"
#include "ir_regs.h"
#include "drv_config.h"
#include "chip_ver.h"
#include "drv_thread.h"
#include "drv_vfd.h"
#ifdef CC_SBOX_HWCTRL
#include "x_pinmux.h"
#endif

#include "sys_config.h"
#include "pm.h"
#include <linux/module.h>


#ifdef VFD_TOOL_CONFIG_MODE
extern void DRS232LogB(BYTE bV1, BYTE bV2, BYTE bV3, BYTE bV4);
#endif

#define IR_API_LOG_LEAST LOG(11, ": %s\n", __FUNCTION__)
#define IR_API_LOG_ERROR LOG(5, ": %s\n", __FUNCTION__)

//simile to "void DRS232LogB(BYTE bV1, BYTE bV2, BYTE bV3, BYTE bV4)"
#ifdef VFD_TOOL_CONFIG_MODE
#define IRRX_SendKey2Rs232(ui1_cmd1, ui1_cmd2, ui1_data1, ui1_data2)    \
        DRS232LogB(ui1_cmd1, ui1_cmd2, ui1_data1, ui1_data2) 
#else
#define IRRX_SendKey2Rs232(ui1_cmd1, ui1_cmd2, ui1_data1, ui1_data2)    \
		LOG(0, "send to rs232: %x %x %x %x\n", ui1_cmd1, ui1_cmd2, ui1_data1, ui1_data2)
#endif

#if (IRRX_RC_PROTOCOL == IRRX_RC_NEC )
#define IRRX_POLL_SPACE_1ST    (70)
#define IRRX_POLL_SPACE_2ST    (115)
#elif (IRRX_RC_PROTOCOL == IRRX_RC_JVC )
#define IRRX_POLL_SPACE_1ST    (70)
#define IRRX_POLL_SPACE_2ST    (70)
#elif (IRRX_RC_PROTOCOL == IRRX_RC_PAN )
#define IRRX_POLL_SPACE_1ST    (165)
#define IRRX_POLL_SPACE_2ST    (165)
#elif (IRRX_RC_PROTOCOL == IRRX_RC_SIRC )
#define IRRX_POLL_SPACE_1ST    (150)
#define IRRX_POLL_SPACE_2ST    (50)
#elif (IRRX_RC_PROTOCOL == IRRX_RC_RCA)
#define IRRX_POLL_SPACE_1ST    (70)
#define IRRX_POLL_SPACE_2ST    (70)
#else
#define IRRX_POLL_SPACE_1ST    (112)
#define IRRX_POLL_SPACE_2ST    (112)
#endif

static BOOL _fgSemaInit = FALSE;
static HANDLE_T _hSemaKey = 0;  /* Only Initial at boot time, even reset or stop, it won't delete. */
static volatile UINT32 _u4CurrKey = BTN_NONE;
static UINT32 _u4PrevKey = BTN_NONE;

#if (IRRX_RC_PROTOCOL == IRRX_RC_NEC)
static UINT32 _u4GroupId = MTK_IRRX_GRPID_DVD;
static UINT32 _u4GroupId1 = MTK_IRRX_GRPID_DVD_1;
static UINT32 _u4GroupId2 = MTK_IRRX_GRPID_DVD_2;
static UINT32 _u4GroupId3 = MTK_IRRX_GRPID_DVD_3;
static UINT32 _u4GroupId4 = MTK_IRRX_GRPID_DVD_4;
static UINT32 _u4_1stPulse = 8;
static UINT32 _u4_2ndPulse = 0;
static UINT32 _u4_3rdPulse = 0;
#endif
static UINT32 _u4IrRxTimeSlice = MTK_IRRX_TIMESLICE - 100;  /* this variable will block the same key in 300 ms now. */
static INT32 _fgRepeat = 0;

#if (CONFIG_DRV_LINUX && CONFIG_ARM2_EJECT)
static HANDLE_T _hIRRXSemaKey = 0;
static BOOL _fgMiscInit = 0;
#endif

/****************************************************************
                      Internal Functions
********************************************************************/
#ifndef   VFD_TOOL_CONFIG_MODE

#if (IRRX_RC_PROTOCOL == IRRX_RC_RC6 )   
 /*! \fn static UINT32 _IRRX_XferRC6ToCrystal(UINT32 u4Info, const UINT8 * pu1Data)
      \brief Map the key code to Crystal button as BTN_DIGITAL_1 and so on.
      \param u4Info  contain the number of decoded code and the value of the 
      sampling counter in the 1st pulse.
      \param  pu1Data  contian the decoded code, including key value and custom 
      value  
      \retval UINT32  BTN_NONE            Invalid key code
                   BTN_KEY_REPEAT              The key is remaining pressed
                   Other BTN_                       New key code.
    */
static UINT32 _IRRX_XferRC6ToCrystal(UINT32 u4Info, const UINT8 * pu1Data, BOOL u1NeedRelease)
{
    UINT32 u4BitCnt = 0;
    static UINT32 u4TogKey = 0;
    UINT32 u4RC6key = 0;

    u4BitCnt = INFO_TO_BITCNT(u4Info);

    /* Check data. */
    if ((u4BitCnt != IRRX_RC6_BITCNT) 
		|| (pu1Data == NULL)
		|| (IRRX_RC6_GET_CUSTOM(pu1Data[0], pu1Data[1]) != IRRX_RC6_CUSTOM)
		|| (IRRX_RC6_GET_LEADER(pu1Data[0]) != IRRX_RC6_LEADER))
    {
        LOG( 9 , "Bitcnt: 0x%02x, Leader: 0x%02x, Custom: 0x%02x\n ", 
			 u4BitCnt, IRRX_RC6_GET_LEADER(pu1Data[0]), IRRX_RC6_GET_CUSTOM(pu1Data[0], pu1Data[1]));
        return BTN_NONE;
    }

    if (u4TogKey != IRRX_RC6_GET_TOGGLE(pu1Data[0]))
    {
        //a new key
        u4RC6key = IRRX_RC6_GET_KEYCODE(pu1Data[1], pu1Data[2]);
		u4TogKey = IRRX_RC6_GET_TOGGLE(pu1Data[0]);
        LOG( 9 , "a RC6 key down: 0x%02x  toggle: 0x%02x\n ", u4RC6key, u4TogKey);

	    if(u4RC6key < IRRX_RC6_MAX_MAP_ENTRY) 
	    {
	      if(TRUE == u1NeedRelease)
	      {
		    _u4PrevKey = _au4RC6CrystalKeyMap[u4RC6key];  
	        return _u4PrevKey;
	      }
		  else
		  {
		    return (_au4RC6CrystalKeyMap[u4RC6key]);
		  }
	    }
		else
		{
		  return BTN_NONE;
		}
    }
    else
    {                           //key hold
       LOG(9, " a RC6 key hold : 0x%02x\n", IRRX_RC6_GET_KEYCODE(pu1Data[1],
                                                              pu1Data[2]));
       if (_fgRepeat)
       {
         return BTN_KEY_REPEAT;
       }
       else
       {
           return _u4PrevKey;
       }
    }
   
}

static INT32 _IRRX_XferCrystalToRC6(UINT32 u4CrystalKey, UINT32* pu4RC6IrM, UINT32* pu4RC6IrL)
{
  UINT32 u4Cnt;

  *pu4RC6IrM = 0x00000000;
  *pu4RC6IrL = 0x00000000;
  
  if((BTN_NONE == u4CrystalKey)||(BTN_NO_DEF == u4CrystalKey))
  {
    LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	return (IR_FAIL);
  }
  else
  {
    for(u4Cnt = 0; u4Cnt < IRRX_RC6_MAX_MAP_ENTRY; u4Cnt ++)
    {
      if(u4CrystalKey == _au4RC6CrystalKeyMap[u4Cnt])
      {
        break;
      }
    }

	if(IRRX_RC6_MAX_MAP_ENTRY == u4Cnt)
	{
	  LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	  return (IR_FAIL);
	}
	else
	{
	  *pu4RC6IrM = (*pu4RC6IrM << 0x06) | (u4Cnt & 0x3F);
	  *pu4RC6IrM = (*pu4RC6IrM << 0x08) | (IRRX_RC6_CUSTOM & 0x3F);
	  *pu4RC6IrM = (*pu4RC6IrM << 0x02) | ((u4Cnt & 0xC0) >> 0x06);
	  *pu4RC6IrM = (*pu4RC6IrM << 0x04) | (IRRX_RC6_LEADER & 0x0F);
	  *pu4RC6IrM = (*pu4RC6IrM << 0x02) | (IRRX_RC6_TOGGLE0 & 0x03);
	  *pu4RC6IrM = (*pu4RC6IrM << 0x02) | ((IRRX_RC6_CUSTOM & 0xC0) >> 0x06);
	}
  }

  return (IR_SUCC);
}

#elif (IRRX_RC_PROTOCOL == IRRX_RC_SIRC )   

static BYTE Reverse1Byte(BYTE ucSrc)
{
  BYTE ucRet=0;
  int i;
  BYTE ucTemp;
  for(i=0;i<8;i++)
  {
 	 ucTemp=1<<i;
 	 if(ucSrc&ucTemp)
 	 {
 		 ucRet+=1<<(7-i);
 	 }
  }
 
  return ucRet;
}

 /*! \fn static UINT32 _IRRX_XferSIRCToCrystal(UINT32 u4Info, const UINT8 * pu1Data)
      \brief Map the key code to Crystal button as BTN_DIGITAL_1 and so on.
      \param u4Info  contain the number of decoded code and the value of the 
            sampling counter in the 1st pulse.
      \param  pu1Data  contian the decoded code, including key value and custom 
      value  
      \retval UINT32  BTN_NONE            Invalid key code
                   BTN_KEY_REPEAT              The key is remaining pressed
                   Other BTN_                       New key code.
    */
static UINT32 _IRRX_XferSIRCToCrystal(UINT32 u4Info, const UINT8 * pu1Data, BOOL u1NeedRelease)
{
    UINT32 u4BitCnt;   
    BYTE u1Command, u1Device, u1Extended;
    static HAL_TIME_T rPrevTime;
    HAL_TIME_T rTime, rDelta;
    BOOL fgRepeatTime;

    HAL_GetTime(&rTime);
    HAL_GetDeltaTime(&rDelta, &rPrevTime, &rTime);
    if ((rDelta.u4Seconds == 0)
         && (rDelta.u4Micros < (1000 * 50)))
    {
       fgRepeatTime = TRUE; 
    }
    else
    {
       fgRepeatTime = FALSE;  
    }
	HAL_GetTime(&rPrevTime);
	
    u4BitCnt = INFO_TO_BITCNT(u4Info);

    /* Check empty data. */
    if ((u4BitCnt == 0) || (pu1Data == NULL))
    {
        // V_IR_FAILED
        return BTN_NONE;
    }

    switch (u4BitCnt)
    {
        case IRRX_SIRC_BITCNT12:

            u1Command = (pu1Data[0] >> 1);
			u1Command = Reverse1Byte(u1Command<<1);
			
			u1Device = ((pu1Data[0] & 0x01) << 4) | ((pu1Data[1] & 0xF0) >> 4);
			u1Device = Reverse1Byte(u1Device<<3);
			
            LOG(2, "Received 12B Key: 0x%02x, Device = 0x%02x\n", u1Command, u1Device);

            if((u1Command >= IRRX_SIRC_MAX_MAP_ENTRY) 
				|| (u1Device != IRRX_SIRC_12B_DEVICE))  
            {
                return BTN_NONE;
            }

            if ((TRUE == u1NeedRelease) && fgRepeatTime && (_u4PrevKey == _au4SIRC12BCrystalKeyMap[u1Command]))
            {
                if(_fgRepeat) 
                {
                    return BTN_KEY_REPEAT;
                }
                else
                {
                    return _u4PrevKey;
                }
            }
			
            if(TRUE == u1NeedRelease)
            {
              _u4PrevKey = _au4SIRC12BCrystalKeyMap[u1Command];
        	  return _u4PrevKey;
            }
            else
            {
              return _au4SIRC12BCrystalKeyMap[u1Command];
            }
				
        case IRRX_SIRC_BITCNT15:
			
            u1Command = (pu1Data[0] >> 1);
			u1Command = Reverse1Byte(u1Command<<1);
			
			u1Device = ((pu1Data[0] & 0x01) << 7) | ((pu1Data[1] & 0xFE) >> 1);
			u1Device = Reverse1Byte(u1Device);
			
            LOG(2, "Received 15B Key: 0x%02x, Device = 0x%02x\n", u1Command, u1Device);

            if((u1Command >= IRRX_SIRC_MAX_MAP_ENTRY) 
				|| (u1Device != IRRX_SIRC_15B_DEVICE))  
            {
                return BTN_NONE;
            }

            if ((TRUE == u1NeedRelease) && fgRepeatTime && (_u4PrevKey == _au4SIRC15BCrystalKeyMap[u1Command]))
            {
                if(_fgRepeat) 
                {
                    return BTN_KEY_REPEAT;
                }
                else
                {
                    return _u4PrevKey;
                }
            }
			
            if(TRUE == u1NeedRelease)
            {
              _u4PrevKey = _au4SIRC15BCrystalKeyMap[u1Command];
        	  return _u4PrevKey;
            }
            else
            {
              return _au4SIRC15BCrystalKeyMap[u1Command];
            }
            
        case IRRX_SIRC_BITCNT20:
			
            u1Command = (pu1Data[0] >> 1);
			u1Command = Reverse1Byte(u1Command<<1);
			
			u1Device = ((pu1Data[0] & 0x01) << 4) | ((pu1Data[1] & 0xF0) >> 4);
			u1Device = Reverse1Byte(u1Device<<3);

			u1Extended = ((pu1Data[1] & 0x0F) << 4) | ((pu1Data[2] & 0xF0) >> 4);
			u1Extended = Reverse1Byte(u1Extended);

			LOG(2, "Received 20B Key: 0x%02x, Device = 0x%02x, u1Extended = 0x%02x\n", u1Command, u1Device, u1Extended);

			if((u1Command >= IRRX_SIRC_MAX_MAP_ENTRY) 
				|| (u1Device != IRRX_SIRC_20B_DEVICE))  
            {
                return BTN_NONE;
            }

            if ((TRUE == u1NeedRelease) && fgRepeatTime && (_u4PrevKey == _au4SIRC20BCrystalKeyMap[u1Command]))
            {
                if(_fgRepeat) 
                {
                    return BTN_KEY_REPEAT;
                }
                else
                {
                    return _u4PrevKey;
                }
            }
			
            if(TRUE == u1NeedRelease)
            {
              _u4PrevKey = _au4SIRC20BCrystalKeyMap[u1Command];
        	  return _u4PrevKey;
            }
            else
            {
              return _au4SIRC20BCrystalKeyMap[u1Command];
            }
			
        default:
            return BTN_NONE;
    }

}

static INT32 _IRRX_XferCrystalToSIRC(UINT32 u4CrystalKey, UINT32* pu4SIRCIrM, UINT32* pu4SIRCIrL)
{
  UINT32 u4Cnt;
  UINT8  u1BitCnt = 0x0; 
  
  *pu4SIRCIrM = 0x00000000;
  *pu4SIRCIrL = 0x00000000;
  
  if((BTN_NONE == u4CrystalKey)||(BTN_NO_DEF == u4CrystalKey))
  {
    LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	return (IR_FAIL);
  }
  else
  {
    for(u4Cnt = 0; u4Cnt < IRRX_SIRC_MAX_MAP_ENTRY; u4Cnt ++)
    {
      if(u4CrystalKey == _au4SIRC12BCrystalKeyMap[u4Cnt])
      {
        u1BitCnt = IRRX_SIRC_BITCNT12;
        break;
      }

	  if(u4CrystalKey == _au4SIRC15BCrystalKeyMap[u4Cnt])
      {
        u1BitCnt = IRRX_SIRC_BITCNT15;
        break;
      }

	  if(u4CrystalKey == _au4SIRC20BCrystalKeyMap[u4Cnt])
      {
        u1BitCnt = IRRX_SIRC_BITCNT20;
        break;
      }
    }

	if(IRRX_SIRC_MAX_MAP_ENTRY == u4Cnt)
	{
	  LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	  return (IR_FAIL);
	}
	else
	{
	  if(u1BitCnt == IRRX_SIRC_BITCNT20)
	  { 
	      *pu4SIRCIrM = ((Reverse1Byte(u4Cnt) & 0xFE)) 
		  	         | ((Reverse1Byte(IRRX_SIRC_20B_EXTENDED) & 0xF0) << 4)
		  	         | ((Reverse1Byte(IRRX_SIRC_20B_EXTENDED) & 0x0F) << 20)
        			 | ((Reverse1Byte(IRRX_SIRC_20B_DEVICE) & 0x78) << 9)
        			 | ((Reverse1Byte(IRRX_SIRC_20B_DEVICE) & 0x80) >> 7);
	  }
	  else if(u1BitCnt == IRRX_SIRC_BITCNT15)
	  {
	  	*pu4SIRCIrM = ((Reverse1Byte(u4Cnt) & 0xFE)) 
			         | ((Reverse1Byte(IRRX_SIRC_15B_DEVICE) & 0x80) >> 7)
			         | ((Reverse1Byte(IRRX_SIRC_15B_DEVICE) & 0x7F) << 9);
	  }
	  else if(u1BitCnt == IRRX_SIRC_BITCNT12)
	  {
	  	*pu4SIRCIrM = ((Reverse1Byte(u4Cnt) & 0xFE)) 
			         | ((Reverse1Byte(IRRX_SIRC_12B_DEVICE) & 0x80) >> 7)
			         | ((Reverse1Byte(IRRX_SIRC_12B_DEVICE) & 0x78) << 9);
	  }
    }
  }
  return (IR_SUCC);
}

#elif (IRRX_RC_PROTOCOL == IRRX_RC_JVC)

static UINT32 _IRRX_XferJVCToCrystal(UINT32 u4Info, const UINT8 * pu1Data, BOOL u1NeedRelease)
{
    UINT32 u4CusId, u4BitCnt, u4Code;
	static UINT32 u4PreCode = 0xFF;

    /* Check empty data. */
    u4BitCnt = INFO_TO_BITCNT(u4Info);
    if ((u4BitCnt == 0) || (pu1Data == NULL))
    {
         return BTN_NONE;
    }

    u4Code = pu1Data[2];
	u4Code = (u4Code << 8) + pu1Data[1];
	u4Code = (u4Code << 8) + pu1Data[0];
	
    /* Check repeat key. */
    if ((u4BitCnt == IRRX_JVC_BITCNT_REPEAT) && (TRUE == u1NeedRelease))
    {
        u4CusId = u4Code & 0xFF;
        u4Code = (u4Code >> 8) & 0xFF;
		
        if ((u4CusId == IRRX_JVC_CUSTOM) && 
			(u4Code == u4PreCode) &&
			(INFO_TO_1STPULSE(u4Info) <= IRRX_JVC_1ST_PULSE_REPEAT)&&
            (INFO_TO_2NDPULSE(u4Info) <= 2) &&
            (INFO_TO_3RDPULSE(u4Info) <= 2))
        {
            LOG(6, "KeyCode is 0x%02x - repeat\n", u4Code);
            if (_fgRepeat)
            {
                return BTN_KEY_REPEAT;
            }
            else
            {
                return _u4PrevKey;
            }
        }
        else
        {
            LOG(10, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
            return BTN_NONE;
        }
    }

    u4Code >>= 1;
    u4CusId = u4Code & 0xFF;
    u4Code = (u4Code >> 8) & 0xFF;
		
    /* Check invalid pulse. */
    if ((u4CusId != IRRX_JVC_CUSTOM)
		|| (u4BitCnt != IRRX_JVC_BITCNT_NORMAL)
        || (INFO_TO_1STPULSE(u4Info) != IRRX_JVC_1ST_PULSE_NORMAL)
        || (INFO_TO_2NDPULSE(u4Info) > (2))
        || (INFO_TO_3RDPULSE(u4Info) > (2)))
    {
        LOG(10, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
        return BTN_NONE;
    }

    u4PreCode = u4Code;
	
    LOG(6, "KeyCode is 0x%02x\n", u4Code);
    if (u4Code >= IRRX_JVC_MAX_MAP_ENTRY)
    {
        LOG(9, "%s(%d) BTN_NONE\n", __FILE__, __LINE__);
        return BTN_NONE;
    }

    if(TRUE == u1NeedRelease)
    {
      _u4PrevKey = _au4JVCCrystalKeyMap[u4Code];
	  return _u4PrevKey;
    }
    else
    {
      return _au4JVCCrystalKeyMap[u4Code];
    }
	
}

static INT32 _IRRX_XferCrystalToJVC(UINT32 u4CrystalKey, UINT32* pu4MtkIrM, UINT32* pu4MtkIrL)
{

  UINT32 u4Cnt;

  *pu4MtkIrM = 0x00000000;
  *pu4MtkIrL = 0x00000000;
  
  if((BTN_NONE == u4CrystalKey)||(BTN_NO_DEF == u4CrystalKey))
  {
    LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	return (IR_FAIL);
  }
  else
  {
    for(u4Cnt = 0; u4Cnt < IRRX_JVC_MAX_MAP_ENTRY; u4Cnt ++)
    {
      if(u4CrystalKey == _au4JVCCrystalKeyMap[u4Cnt])
      {
        break;
      }
    }

	if(IRRX_JVC_MAX_MAP_ENTRY == u4Cnt)
	{
	  LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	  return (IR_FAIL);
	}
	else
	{
	  *pu4MtkIrM = (u4Cnt & 0xFF);
	  *pu4MtkIrM = (*pu4MtkIrM << 8) | (IRRX_JVC_CUSTOM & 0xFF);
	  *pu4MtkIrM = (*pu4MtkIrM << 1) | (1);
	}
  }

  return (IR_SUCC);
}

#elif (IRRX_RC_PROTOCOL == IRRX_RC_PAN)

static UINT32 _IRRX_XferPANToCrystal(UINT32 u4Info, const UINT8 * pu1Data, BOOL u1NeedRelease)
{
    UINT16 u2CusId = 0;
    UINT8 u1SysBit = 0;
    UINT8 u1DeviceBit = 0;
    UINT8 u1CommandBit = 0;
    UINT8 u1ParityBit = 0;

    UINT32 u4BitCnt = 0;
///    static UINT32 u4PreCode = 0xFF;

    /* Check empty data. */
    u4BitCnt = INFO_TO_BITCNT(u4Info);
    if ((u4BitCnt == 0) || (pu1Data == NULL))
    {
         return BTN_NONE;
    }

    u2CusId = pu1Data[1];
    u2CusId = (u2CusId << 8) + pu1Data[0];
    u1SysBit = pu1Data[2];
    u1DeviceBit = pu1Data[3];
    u1CommandBit = pu1Data[4];
    u1ParityBit = pu1Data[5];
	
    /* Check invalid pulse. */
    if((IRRX_PAN_CUSTOM != u2CusId) || 				///customer ID should be fixed as 0x2002
		(0x00 != (u1SysBit & 0x0F)) ||				/// customer parity ID should be fixed as 0
		(u1ParityBit != (u1SysBit ^ u1DeviceBit ^ u1CommandBit)))	///verify value
    {
        LOG(10, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
        return BTN_NONE;
    }

    if((0x80 != (u1SysBit & 0xF0)) && (0x90 != (u1SysBit & 0xF0)) && (0xB0 != (u1SysBit & 0xF0)))		///System ID should be reasonable value
    {
        LOG(10, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	 return BTN_NONE;
    }

    if(IRRX_PAN_BITCNT_NORMAL != u4BitCnt)		///not normal bit count..
    {
        LOG(10, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	 return BTN_NONE;
    }

    LOG(6, "KeyCode is 0x%02x %02x %02x \n", (u1SysBit & 0xF0), (0x0F & u1DeviceBit) , (u1CommandBit));

    if(0x90 == (u1SysBit & 0xF0))
    {
	    switch (u1CommandBit)
	    {
	        case 0xB1 :
			return BTN_CUSTOM_1;		///(Amplifier region) Volum Up
		 	break;
		 case 0xB2 :
			return BTN_CUSTOM_2;		///(Amplifier region)Volum Down
			break;
	        default:
			return BTN_NONE;
			break;
	    }
    }
    else if(0x80 == (u1SysBit & 0xF0))
    {
	    switch (u1CommandBit)
	    {
	        case 0x3D :
			return BTN_CUSTOM_3;		///(TV region)Power 
		 	break;
		 case 0x05 :
			return BTN_CUSTOM_4;		///(TV region)Input
			break;
	        case 0x20 :
			return BTN_CUSTOM_5;		///(TV region)Volum Up 
		 	break;
		 case 0x21 :
			return BTN_CUSTOM_6;		///(TV region)Volum Down
			break;
	        case 0x34 :
			return BTN_CUSTOM_7;		///(TV region)Channel Up 
		 	break;
		 case 0x35 :
			return BTN_CUSTOM_8;		///(TV region)Channel Down
			break;
	        default:
			return BTN_NONE;
			break;
	    }
    }
    else
    {
	    if(0x01 == (0x0F & u1DeviceBit))
	    {
		    switch (u1CommandBit)
		    {
		        case 0x56 :
				return BTN_PIP;
			 	break;
			 case 0x51 :
				return BTN_EXIT;
				break;
		        case 0x50 :
				return BTN_POP;
			 	break;
			 case 0x41 :
				return BTN_RED;
				break;
		        case 0x42 :
				return BTN_GREEN;
			 	break;
			 case 0x43 :
				return BTN_YELLOW;
				break;
			 case 0x40 :
				return BTN_BLUE;
				break;
		        case 0x57 :
				return BTN_PIP_AUDIO;
			 	break;
			 case 0x82 :
				return BTN_CUSTOM_9;
				break;
		        default:
				return BTN_NONE;
				break;
		    }		
	    }
	    else if(0x00 == (0x0F & u1DeviceBit))
	    {
      		    return _au4PANCrystalKeyMap[u1CommandBit];
	    }
	    else
	    {
	     	    return BTN_NONE;
	    }
    }
}

static INT32 _IRRX_XferCrystalToPAN(UINT32 u4CrystalKey, UINT32* pu4MtkIrM, UINT32* pu4MtkIrL)
{

  UINT32 u4Cnt;

  *pu4MtkIrM = 0x00000000;
  *pu4MtkIrL = 0x00000000;
  
  if((BTN_NONE == u4CrystalKey)||(BTN_NO_DEF == u4CrystalKey))
  {
    LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	return (IR_FAIL);
  }
  else
  {
    for(u4Cnt = 0; u4Cnt < IRRX_PAN_MAX_MAP_ENTRY; u4Cnt ++)
    {
      if(u4CrystalKey == _au4PANCrystalKeyMap[u4Cnt])
      {
        break;
      }
    }

	if(IRRX_PAN_MAX_MAP_ENTRY == u4Cnt)
	{
	  LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	  return (IR_FAIL);
	}
	else
	{
	  *pu4MtkIrM = (u4Cnt & 0xFF);
	  *pu4MtkIrM = (*pu4MtkIrM << 8) | (IRRX_PAN_CUSTOM & 0xFFFF);
	  *pu4MtkIrM = (*pu4MtkIrM << 1) | (1);
	}
  }

  return (IR_SUCC);
}

#elif (IRRX_RC_PROTOCOL == IRRX_RC_RC5 )   
 /*! \fn static UINT32 _IRRX_XferRC5ToCrystal(UINT32 u4Info, const UINT8 * pu1Data)
      \brief Map the key code to Crystal button as BTN_DIGITAL_1 and so on.
      \param u4Info  contain the number of decoded code and the value of the 
      sampling counter in the 1st pulse.
      \param  pu1Data  contian the decoded code, including key value and custom 
      value  
      \retval UINT32  BTN_NONE            Invalid key code
                   BTN_KEY_REPEAT              The key is remaining pressed
                   Other BTN_                       New key code.
    */
static UINT32 _IRRX_XferRC5ToCrystal(UINT32 u4Info, const UINT8 * pu1Data, BOOL u1NeedRelease)
{
    UINT32 u4BitCnt = 0;
    static UINT32 u4TogKey = 0xFF;
    static UINT32 u4RC5key = 0xFF;

    u4BitCnt = INFO_TO_BITCNT(u4Info);

    /* Check data. */
    if ((u4BitCnt != IRRX_RC5_BITCNT) 
		|| (pu1Data == NULL)
		|| (IRRX_RC5_GET_CUSTOM(pu1Data[0]) != IRRX_RC5_CUSTOM))
    {
        return BTN_NONE;
    }

    if (u4TogKey != IRRX_RC5_GET_TOGGLE(pu1Data[0]))
    {
        //a new key
        u4RC5key = IRRX_RC5_GET_KEYCODE(pu1Data[0], pu1Data[1]);
		u4TogKey = IRRX_RC5_GET_TOGGLE(pu1Data[0]);
        LOG(2, "a RC5 key down: 0x%02x  toggle: 0x%02x\n ", u4RC5key, u4TogKey);

	    if(u4RC5key < IRRX_RC5_MAX_MAP_ENTRY) 
	    {
	      if(TRUE == u1NeedRelease)
	      {
		    _u4PrevKey = _au4RC5CrystalKeyMap[u4RC5key];  
	        return _u4PrevKey;
	      }
		  else
		  {
		    return (_au4RC5CrystalKeyMap[u4RC5key]);
		  }
	    }
		else
		{
		  return BTN_NONE;
		}
    }
    else if(u4RC5key == IRRX_RC5_GET_KEYCODE(pu1Data[0], pu1Data[1]))
    {//key hold
       LOG(2, " a RC5 key hold : 0x%02x\n", u4RC5key);
       if (_fgRepeat)
       {
         return BTN_KEY_REPEAT;
       }
       else
       {
           return _u4PrevKey;
       }
    }

	return BTN_NONE;
}

static INT32 _IRRX_XferCrystalToRC5(UINT32 u4CrystalKey, UINT32* pu4RC5IrM, UINT32* pu4RC5IrL)
{
  UINT32 u4Cnt;

  *pu4RC5IrM = 0x00000E00;
  *pu4RC5IrL = 0x00000000;
  
  if((BTN_NONE == u4CrystalKey)||(BTN_NO_DEF == u4CrystalKey))
  {
    LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	return (IR_FAIL);
  }
  else
  {
    for(u4Cnt = 0; u4Cnt < IRRX_RC5_MAX_MAP_ENTRY; u4Cnt ++)
    {
      if(u4CrystalKey == _au4RC5CrystalKeyMap[u4Cnt])
      {
        break;
      }
    }

	if(IRRX_RC5_MAX_MAP_ENTRY == u4Cnt)
	{
	  LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	  return (IR_FAIL);
	}
	else
	{
	  *pu4RC5IrM |= (((~IRRX_RC5_CUSTOM) & 0x1F) << 2);
	  *pu4RC5IrM |= (((~u4Cnt) & 0x30) >> 4);
	  *pu4RC5IrM |= (((~u4Cnt) & 0x0F) << 12);
	}
  }

  return (IR_SUCC);
}


#elif (IRRX_RC_PROTOCOL == IRRX_RC_RCA)

static BYTE Reverse1Byte(BYTE ucSrc)
{
  BYTE ucRet=0;
  int i;
  BYTE ucTemp;
  for(i=0;i<8;i++)
  {
 	 ucTemp=1<<i;
 	 if(ucSrc&ucTemp)
 	 {
 		 ucRet+=1<<(7-i);
 	 }
  }
 
  return ucRet;
}

static UINT32 _IRRX_XferRCAToCrystal(UINT32 u4Info, const UINT8 * pu1Data, BOOL u1NeedRelease)
{
    UINT32  u4BitCnt;
    BYTE u1DataCode, u1CustomCode, u1DataCodeVerf, u1CustomCodeVerf;

    u4BitCnt = INFO_TO_BITCNT(u4Info);

    /* Check empty data. */
    if ((u4BitCnt == 0) || (pu1Data == NULL))
    {
           return BTN_NONE;
    }

    /* Check invalid pulse. */
    if (u4BitCnt != IRRX_RCA_BITCNT_NORMAL)
    {
        LOG(11, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
        return BTN_NONE;
    }

    u1CustomCode = pu1Data[0] & 0x0f;
    u1CustomCodeVerf = (pu1Data[1] & 0xf0) >>4;

    if((0xf != (u1CustomCode + u1CustomCodeVerf)) 
		|| (IRRX_RCA_CUSTOM != u1CustomCode))
    {
        LOG(11, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
        return BTN_NONE;
    }
	
    u1DataCode = ((pu1Data[1] & 0x0f) << 4) + ((pu1Data[0] & 0xf0) >>4);
    u1DataCodeVerf = pu1Data[2];

    if(0xff != (u1DataCode + u1DataCodeVerf)) 
    {
        LOG(11, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
        return BTN_NONE;
    }
	
    u1DataCode = Reverse1Byte(u1DataCode);
    u1DataCode = ~u1DataCode;
    LOG(7, "KeyCode is 0x%02x\n", u1DataCode);

    if(TRUE == u1NeedRelease)
    {
      _u4PrevKey = _au4RCACrystalKeyMap[u1DataCode];
	  return _u4PrevKey;
    }
    else
    {
      return _au4RCACrystalKeyMap[u1DataCode];
    }
	
}

static INT32 _IRRX_XferCrystalToRCA(UINT32 u4CrystalKey, UINT32* pu4MtkIrM, UINT32* pu4MtkIrL)
{ 
  UINT32 u4Cnt;

  *pu4MtkIrM = 0x00000000;
  *pu4MtkIrL = 0x00000000;
  
  if((BTN_NONE == u4CrystalKey)||(BTN_NO_DEF == u4CrystalKey))
  {
    LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	return (IR_FAIL);
  }
  else
  {
    for(u4Cnt = 0; u4Cnt < 0XFF; u4Cnt ++)
    {
      if(u4CrystalKey == _au4RCACrystalKeyMap[u4Cnt])
      {
        break;
      }
    }

	if(0XFF == u4Cnt)
	{
	  LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	  return (IR_FAIL);
	}
	else
	{
	  *pu4MtkIrM = 0XFF - (u4Cnt & 0xFF);
	  *pu4MtkIrM = (*pu4MtkIrM << 8) | (u4Cnt & 0xFF);
	  *pu4MtkIrM = (*pu4MtkIrM << 16) | (IRRX_RCA_CUSTOM & 0xFFFF);
	}
  }
  
  return (IR_SUCC);
}

#else      
 /*! \fn static UINT32 _IRRX_XferMtkToCrystal(UINT32 u4Info, const UINT8 * pu1Data)
      \brief Map the key code to Crystal button as BTN_DIGITAL_1 and so on.
      \param u4Info  contain the number of decoded code and the value of the 
            sampling counter in the 1st pulse.
      \param  pu1Data  contian the decoded code, including key value and custom 
      value  
      \retval UINT32  BTN_NONE            Invalid key code
                   BTN_KEY_REPEAT              The key is remaining pressed
                   Other BTN_                       New key code.
    */
static UINT32 _IRRX_XferMtkToCrystal(UINT32 u4Info, const UINT8 * pu1Data, BOOL u1NeedRelease)
{
    UINT32 u4GrpId, u4BitCnt;

    u4BitCnt = INFO_TO_BITCNT(u4Info);

    /* Check empty data. */
    if ((u4BitCnt == 0) || (pu1Data == NULL))
    {
           return BTN_NONE;
    }

    /* Check repeat key. */
    if ((u4BitCnt == MTK_IRRX_BITCNT_REPEAT) && (TRUE == u1NeedRelease))
    {
        if (((INFO_TO_1STPULSE(u4Info) == MTK_IRRX_1st_Plus_REPEAT) ||
             (INFO_TO_1STPULSE(u4Info) == MTK_IRRX_1st_Plus_REPEAT + 1)) &&
            (INFO_TO_2NDPULSE(u4Info) == 0) &&
            (INFO_TO_3RDPULSE(u4Info) == 0))
        {
            if (_fgRepeat)
            {
                //modify by msz00420 07-09-10 for resolving BTN_REPEAT multi-defined in
                //drv_ir.h and u_irrc_btn_def.h
                /*last code
                   return BTN_REPEAT;
                 */
                return BTN_KEY_REPEAT;
                //modify end
            }
            else
            {
                return _u4PrevKey;
            }
        }
        else
        {
            LOG(10, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
            return BTN_NONE;
        }
    }

    /* Check invalid pulse. */
    if (u4BitCnt != MTK_IRRX_BITCNT_NORMAL)
    {
        LOG(10, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
        return BTN_NONE;
    }


    u4GrpId = pu1Data[1];
    u4GrpId = (u4GrpId << 8) + pu1Data[0];

    /* Check GroupId. */
    if (u4GrpId != _u4GroupId && u4GrpId != _u4GroupId1&&(u4GrpId != _u4GroupId2) && (u4GrpId != _u4GroupId3)&& (u4GrpId != _u4GroupId4))
    {
        LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
        return BTN_NONE;
    }

    /* Check invalid key. */
    if ((pu1Data[2] + pu1Data[3]) != MTK_IRRX_BIT8_VERIFY)
    {
        LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
        return BTN_NONE;
    }

    /* Here, pu1Data[2] is the key of MTKDVD remote controller. */
    LOG(7, "KeyCode is 0x%02x\n", pu1Data[2]);
    if (pu1Data[2] >= MTK_NEC_MAX_MAP_ENTRY)
    {
        LOG(9, "%s(%d) BTN_NONE\n", __FILE__, __LINE__);
        return BTN_NONE;
    }

    if(TRUE == u1NeedRelease)
    {
        LOG(10, "%s(%d) cuihuijuan BTN_NONE\n", __FUNCTION__, __LINE__);
         if(u4GrpId == _u4GroupId)
        {
    	     _u4PrevKey =_au4MtkCrystalKeyMap[pu1Data[2]];
        }
		else if(u4GrpId == _u4GroupId1 || u4GrpId == _u4GroupId2 )
        {
    	     _u4PrevKey =_au4MtkCrystalKeyMap1[pu1Data[2]];
        }
        else if(u4GrpId == _u4GroupId3)
        {
    	    _u4PrevKey =_au4MtkCrystalKeyMap2[pu1Data[2]];
        }
        else
        {
		 	_u4PrevKey =_au4MtkCrystalKeyMap3[pu1Data[2]];
        }
        /* _u4PrevKey =(u4GrpId == _u4GroupId)?_au4MtkCrystalKeyMap[pu1Data[2]]: _au4MtkCrystalKeyMap2[pu1Data[2]];*/
	      return _u4PrevKey;
    }
    else
    {   
    	 LOG(10, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
         if(u4GrpId == _u4GroupId)
       {
    	    _u4PrevKey =_au4MtkCrystalKeyMap[pu1Data[2]];
        }
		else if(u4GrpId == _u4GroupId1 || u4GrpId == _u4GroupId2 )
        {
    	     _u4PrevKey =_au4MtkCrystalKeyMap1[pu1Data[2]];
       }
	   else if(u4GrpId == _u4GroupId3)
		{
		     _u4PrevKey =_au4MtkCrystalKeyMap2[pu1Data[2]];
		}
       else
       {
		     _u4PrevKey =_au4MtkCrystalKeyMap3[pu1Data[2]];
       }
	     return _u4PrevKey;
     /* return (u4GrpId == _u4GroupId)?_au4MtkCrystalKeyMap[pu1Data[2]]: _au4MtkCrystalKeyMap2[pu1Data[2]];*/
    }
	
}

static INT32 _IRRX_XferCrystalToMtk(UINT32 u4CrystalKey, UINT32* pu4MtkIrM, UINT32* pu4MtkIrL)
{
  UINT32 u4Cnt;

  *pu4MtkIrM = 0x00000000;
  *pu4MtkIrL = 0x00000000;
  
  if((BTN_NONE == u4CrystalKey)||(BTN_NO_DEF == u4CrystalKey))
  {
    LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	return (IR_FAIL);
  }
  else
  {
    for(u4Cnt = 0; u4Cnt < MTK_NEC_MAX_MAP_ENTRY; u4Cnt ++)
    {
      if(u4CrystalKey == _au4MtkCrystalKeyMap[u4Cnt])
      {
        break;
      }
    }

	if(MTK_NEC_MAX_MAP_ENTRY == u4Cnt)
	{
	  LOG(9, "%s(%d) BTN_NONE\n", __FUNCTION__, __LINE__);
	  return (IR_FAIL);
	}
	else
	{
	  *pu4MtkIrM = MTK_IRRX_BIT8_VERIFY - (u4Cnt & 0xFF);
	  *pu4MtkIrM = (*pu4MtkIrM << 8) | (u4Cnt & 0xFF);
	  *pu4MtkIrM = (*pu4MtkIrM << 16) | (_u4GroupId & 0xFFFF);
	}
  }

  return (IR_SUCC);
}
#endif
#endif 

#if (CONFIG_CHIP_VER_CURR == CONFIG_CHIP_VER_MT8520)
INT32 IRRX_RegResetIrKey(UINT32 u4ResetIr) 
{
   UINT32 u4ResetIrM, u4ResetIrL;
   INT32 i32Ret;
   
#if (IRRX_RC_PROTOCOL == IRRX_RC_NEC )
   
   i32Ret = _IRRX_XferCrystalToMtk(u4ResetIr, &u4ResetIrM, &u4ResetIrL);
   
#elif (IRRX_RC_PROTOCOL == IRRX_RC_RC6) 
   
   i32Ret = _IRRX_XferCrystalToRC6(u4ResetIr, &u4ResetIrM, &u4ResetIrL);
 
#elif (IRRX_RC_PROTOCOL == IRRX_RC_SIRC) 
   
   i32Ret = _IRRX_XferCrystalToSIRC(u4ResetIr, &u4ResetIrM, &u4ResetIrL);

#elif (IRRX_RC_PROTOCOL == IRRX_RC_JVC) 
   
   i32Ret = _IRRX_XferCrystalToJVC(u4ResetIr, &u4ResetIrM, &u4ResetIrL);

#elif (IRRX_RC_PROTOCOL == IRRX_RC_PAN) 
   
   i32Ret = _IRRX_XferCrystalToPAN(u4ResetIr, &u4ResetIrM, &u4ResetIrL);

#elif (IRRX_RC_PROTOCOL == IRRX_RC_RC5) 
   
   i32Ret = _IRRX_XferCrystalToRC5(u4ResetIr, &u4ResetIrM, &u4ResetIrL);

#elif (IRRX_RC_PROTOCOL == IRRX_RC_RCA) 
   
   i32Ret = _IRRX_XferCrystalToRCA(u4ResetIr, &u4ResetIrM, &u4ResetIrL);

#else  //default is nec protol 
   
   i32Ret = _IRRX_XferCrystalToMtk(u4ResetIr, &u4ResetIrM, &u4ResetIrL);
   
#endif
 
   if(IR_SUCC == i32Ret)
   {
	 LOG(9, "Register reset ir(reg) : 0x%08x%08x\n", u4ResetIrL, u4ResetIrM);
	 
	 BIM_RegResetIrKey(u4ResetIrM, u4ResetIrL);
   }
   else
   {
	 LOG(9, "Register reset-IR fail!\n");
   }
 
   return i32Ret;
}
 
UINT32 IRRX_GetRegResetIrKey(void)
{
   UINT32 u4IrRxData[2];
   UINT32 u4ResetIr;
   
   BIM_GetResetIrKey(&u4IrRxData[0], &u4IrRxData[1]);
   
   LOG(9, "Get reset ir(reg) : 0x%08x%08x\n", u4IrRxData[1], u4IrRxData[0]);
 
   
#if (IRRX_RC_PROTOCOL == IRRX_RC_NEC )
   
   u4ResetIr = _IRRX_XferMtkToCrystal((MTK_IRRX_BITCNT_NORMAL | ((_u4_1stPulse & 0xFF) << 8) | ((_u4_2ndPulse & 0xFF) << 16) | ((_u4_3rdPulse & 0xFF) << 24)), (UINT8 *)u4IrRxData, FALSE);
   
#elif (IRRX_RC_PROTOCOL == IRRX_RC_RC6) 
   
   u4ResetIr = _IRRX_XferRC6ToCrystal(IRRX_RC6_BITCNT, (UINT8 *)u4IrRxData, FALSE);
   
#elif (IRRX_RC_PROTOCOL == IRRX_RC_SIRC) 
   
   u4ResetIr = _IRRX_XferSIRCToCrystal(IRRX_SIRC_BITCNT20, (UINT8 *)u4IrRxData, FALSE);

#elif (IRRX_RC_PROTOCOL == IRRX_RC_JVC) 
   
   u4ResetIr = _IRRX_XferJVCToCrystal((IRRX_JVC_BITCNT_NORMAL | (IRRX_JVC_1ST_PULSE_NORMAL << 8)), (UINT8 *)u4IrRxData, FALSE);

#elif (IRRX_RC_PROTOCOL == IRRX_RC_PAN) 
   
   u4ResetIr = _IRRX_XferPANToCrystal((IRRX_PAN_BITCNT_NORMAL | (IRRX_PAN_1ST_PULSE_NORMAL << 8)), (UINT8 *)u4IrRxData, FALSE);

#elif (IRRX_RC_PROTOCOL == IRRX_RC_RC5) 
   
   u4ResetIr = _IRRX_XferRC5ToCrystal(IRRX_RC5_BITCNT, (UINT8 *)u4IrRxData, FALSE);
      
#elif (IRRX_RC_PROTOCOL == IRRX_RC_RCA) 
   
   u4ResetIr = _IRRX_XferRCAToCrystal((IRRX_RCA_BITCNT_NORMAL | (IRRX_RCA_1ST_PULSE_NORMAL << 8)), (UINT8 *)u4IrRxData, FALSE);
#else  //default is nec protol 
   
   u4ResetIr = _IRRX_XferMtkToCrystal((MTK_IRRX_BITCNT_NORMAL | ((_u4_1stPulse & 0xFF) << 8) | ((_u4_2ndPulse & 0xFF) << 16) | ((_u4_3rdPulse & 0xFF) << 24)), (UINT8 *)u4IrRxData, FALSE);
   
#endif

   LOG(9, "Reset ir is(crystal): 0x%08x\n", u4ResetIr);
 
   return u4ResetIr;

}

void IRRX_SetSysHaltResetMode(UINT32 u4Mode)
{
  BIM_SetSysHaltResetMode(u4Mode);
}

UINT32 IRRX_GetSysHaltResetMode(void)
{
  return BIM_GetSysHaltResetMode();
}
#endif
 /*! \fn static void _IRRX_MtkIrCallback(UINT32 u4Info, const UINT8 * pu1Data)
      \brief Callback function. If new key is pressed, Set the global variable 
      _u4CurrKey ,and unlock the semaphore. Next polling in IR thread will 
      notice this change, and send event to IO manager.
      \param u4Info  contain the number of decoded code and the value of the 
            sampling counter in the 1st pulse.
      \param  pu1Data  contian the decoded code, including key value and custom 
      value  
  */
 #ifdef VFD_TOOL_CONFIG_MODE
 static void _IRRX_MtkIrCallback(UINT32 u4Info, const UINT8 * pu1Data)
 {
     UINT32 u4BitCnt;

     u4BitCnt = INFO_TO_BITCNT(u4Info);

    /* Check empty data. */
    if ((u4BitCnt == 0) || (pu1Data == NULL))
    {
           return ;
    }

    if (  u4BitCnt != MTK_IRRX_BITCNT_NORMAL)
    {
          return ;
    }    

    /* Send custom code */
    IRRX_SendKey2Rs232(0xf1, 0xf1,  pu1Data[0],pu1Data[0]);
    /*Send subcuteom code*/
    IRRX_SendKey2Rs232(0xf2, 0xf2,  pu1Data[1],pu1Data[1]);
    /*send key code */
    IRRX_SendKey2Rs232(0xf3, 0xf3,  pu1Data[2],pu1Data[2]);
    return ;
 }
 #else
static void _IRRX_MtkIrCallback(UINT32 u4Info, const UINT8 * pu1Data)
{
    INT32 i4Ret;
    UINT32 u4CrystalKey;
    static UINT32 u4PrevKey = BTN_NONE;
    static HAL_TIME_T rPrevTime;
    HAL_TIME_T rTime, rDelta;
    //UINT32 u4M, u4L;
	
	rDelta.u4Micros = 0;
    rDelta.u4Seconds = 0;
	
    if ((u4Info == 0) || (pu1Data == NULL))
    {
        ASSERT(0);
        LINT_SUPPRESS_NEXT_EXPRESSION(527);
        return;
    }

    #if (IRRX_RC_PROTOCOL == IRRX_RC_NEC )
        u4CrystalKey = _IRRX_XferMtkToCrystal(u4Info, pu1Data, TRUE);
    
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_RC6) 
        u4CrystalKey = _IRRX_XferRC6ToCrystal(u4Info, pu1Data, TRUE);
	
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_SIRC) 
       u4CrystalKey = _IRRX_XferSIRCToCrystal(u4Info, pu1Data, TRUE);
	
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_JVC) 
       u4CrystalKey = _IRRX_XferJVCToCrystal(u4Info, pu1Data, TRUE);
	
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_PAN) 
       u4CrystalKey = _IRRX_XferPANToCrystal(u4Info, pu1Data, TRUE);
	
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_RC5) 
        u4CrystalKey = _IRRX_XferRC5ToCrystal(u4Info, pu1Data, TRUE);
	
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_RCA) 
       u4CrystalKey = _IRRX_XferRCAToCrystal(u4Info, pu1Data, TRUE);
	
    #else  //default is nec protol 
        u4CrystalKey = _IRRX_XferMtkToCrystal(u4Info, pu1Data, TRUE);
    
    #endif
	
    //_IRRX_XferCrystalToRC5(u4CrystalKey, &u4M, &u4L);
    //LOG(2," ****** 0x%08x\n", u4M);
   

#if (CONFIG_DRV_LINUX && CONFIG_ARM2_EJECT)
    if(u4CrystalKey == BTN_EJECT)
    {
        if(_hIRRXSemaKey)
        {
            if (x_sema_unlock(_hIRRXSemaKey) != OSR_OK)
            {
                LOG(1, "%s(%d) _hIRRXSemaKey is fault.\n", __FILE__, __LINE__);
            }
        }
    }
#endif

    if (u4CrystalKey == BTN_NONE)
    {

        return;
    }
    LOG(7, "u4CrystalKey is 0x%08x\n", u4CrystalKey);

    if (!_fgRepeat && (u4PrevKey == u4CrystalKey))
    {
        HAL_GetTime(&rTime);
        HAL_GetDeltaTime(&rDelta, &rPrevTime, &rTime);
        if ((rDelta.u4Seconds == 0)
            && (rDelta.u4Micros < (1000 * _u4IrRxTimeSlice)))
        {
            LOG(7, "Repeat code but in 400 timeslice.\n");
            return;
        }
        else
        {
            HAL_GetTime(&rPrevTime);
        }
    }
    else
    {
        u4PrevKey = u4CrystalKey;
        HAL_GetTime(&rPrevTime);
    }

    if(_u4CurrKey != BTN_NONE)
    {
       return;
    }

    if(_fgSemaInit == TRUE)
    {
    _u4CurrKey = u4CrystalKey;
    i4Ret = x_sema_unlock(_hSemaKey);
    if (i4Ret != OSR_OK)
    {
        LOG(1, "%s(%d) SemaKey is fault. i4Ret:%d\n", __FILE__, __LINE__,
            i4Ret);
    }
    }
	
    return;
}
#endif

#ifdef CC_SBOX_HWCTRL
static void _GPIO_IrKeyCallback(INT32 i4Gpio, BOOL fgStatus)
{
    static HAL_TIME_T rLastTime = { 0, 0 };
    HAL_TIME_T rNowTime, rDiffTime;

    HAL_GetTime(&rNowTime);
    HAL_GetDeltaTime(&rDiffTime, &rLastTime, &rNowTime);
    if ((rDiffTime.u4Seconds < 1) && (rDiffTime.u4Micros < (300 * 1000)))
    {
        LOG(9, "IR duplicated Gpio is %d\n", i4Gpio);
        return;
    }

    rLastTime.u4Seconds = rNowTime.u4Seconds;
    rLastTime.u4Micros = rNowTime.u4Micros;
    LOG(9, "IR Gpio is %d\n", i4Gpio);
    switch (i4Gpio)
    {
        case 61:
            IRRX_SendMtkIr(BTN_POWER);
            break;
        case 122:
            IRRX_SendMtkIr(BTN_CURSOR_UP);
            break;
        case 123:
            IRRX_SendMtkIr(BTN_CURSOR_DOWN);
            break;
        case 124:
            IRRX_SendMtkIr(BTN_CURSOR_RIGHT);
            break;
        case 125:
            IRRX_SendMtkIr(BTN_CURSOR_LEFT);
            break;
        case 126:
            IRRX_SendMtkIr(BTN_MENU);
            break;
        case 127:
            IRRX_SendMtkIr(BTN_SELECT);
            break;
        default:
            break;
    }
}
#endif

/*! \fn void IRRX_SetEnable(BOOL fgEnable)
    */
void IRRX_SetEnable(BOOL fgEnable)
{
   IRHW_SetEnable(fgEnable);
   
   LOG(0, "%s (%d)\n", __FUNCTION__, fgEnable);
}
EXPORT_SYMBOL(IRRX_SetEnable);

#if (CONFIG_DRV_LINUX && CONFIG_ARM2_EJECT)
/*! \fn void IRRX_SetMiscInit(BOOL fgMiscInit)
    */
void IRRX_SetMiscInit(BOOL fgMiscInit)
{
   _fgMiscInit = fgMiscInit;

   LOG(0, "%s (%d)\n", __FUNCTION__, fgMiscInit);
}
EXPORT_SYMBOL(IRRX_SetMiscInit);

/*! \fn static void IRRX_DataProcessingThread(void* pvArg)
    */
static void IRRX_DataProcessingThread(void* pvArg)
{
    INT32 i4Ret;

    while(1)
    {
        if(_fgMiscInit)
        {
            break;
        }
	
        i4Ret = x_sema_lock_timeout(_hIRRXSemaKey, 100);
        if (i4Ret == OSR_OK)
        {
            IRHW_FastejectKeyNotify();      
        }
        else if (i4Ret == OSR_TIMEOUT)
        {
            continue;
        }
        else
        {
            ASSERT((i4Ret == OSR_OK) || (i4Ret == OSR_TIMEOUT));
        }
    }

	if(_hIRRXSemaKey)
	{
	    HANDLE_T hDelIRRXSemaKey;

            // [2009.11.27, Toby] BDP00126341, BDP00126290
	    // Must set global variable to 0 first to avoid interrupt function
	    // is triggered between delete semaphore and reset global variable
	    hDelIRRXSemaKey = _hIRRXSemaKey;
	    _hIRRXSemaKey = 0;

	    // It's safe to delete semaphore now
	    //if(x_sema_delete(_hIRRXSemaKey) != OSR_OK)
	    if(x_sema_delete(hDelIRRXSemaKey) != OSR_OK)
	    {
	        LOG(0, "%s, Error: Delete _hIRRXSemaKey fail\n", __FUNCTION__);
	    }
	}

    LOG(0, "%s, exit\n", __FUNCTION__);
	x_thread_exit();
}

/*! \fn INT32 IRRX_FastEject_Init(void)
    */
INT32 IRRX_FastEject_Init(void)
{
    HANDLE_T  hIRRXThread;

    _fgMiscInit = 0;
	  
    if (x_sema_create(&_hIRRXSemaKey, X_SEMA_TYPE_BINARY, X_SEMA_STATE_LOCK) != OSR_OK)
    {
        LOG(0, "%s, Error: Create _hIRRXSemaKey fail\n", __FUNCTION__);
        return IR_FAIL;
    }
		
     /* Create IRRX data processing thread */
     if (x_thread_create(&hIRRXThread,
    					 IRRX_THREAD_NAME,
    					 IRRX_STACK_SIZE,
    					 IRRX_THREAD_PRIORITY,
    					 IRRX_DataProcessingThread,
    					 0,
    					 NULL) != OSR_OK)
     {
    	 LOG(0, "%s, Error: Create thread fail\n", __FUNCTION__);
     	 
    	 return (IR_FAIL);
     }

	 return IR_SUCC;
}
#else
/*! \fn void IRRX_SetMiscInit(BOOL fgMiscInit)
    */
void IRRX_SetMiscInit(BOOL fgMiscInit)
{
   LOG(0, "%s (%d)\n", __FUNCTION__, fgMiscInit);
}
#endif

#if CONFIG_SUSPEND_TO_DRAM
extern void IR_RxEnable(BOOL fgEnable);

int i4IR_Suspend(void *param)
{
    LOG(6, "IR Suspend Callback!\n ");
    IR_RxEnable(FALSE);
	return 0;
}

extern INT32 IRRX_Partial_ResetMtkIr(void);
int i4IR_Resume(void *param)
{	
    LOG(6, "IR Resume Callback!\n ");
    IR_RxEnable(TRUE);
    IRRX_Partial_ResetMtkIr();
//    IRRX_ResetMtkIr();
	return 0;
}

struct pm_operations irrx_pm_ops = {
.suspend = i4IR_Suspend,
.resume = i4IR_Resume,
};
#endif

 /*! \fn INT32 IRRX_InitMtkIr(void)
      \brief Init IR module, including reset ISR, set callback function for key 
      code processing, and init hardware according to the IRRX_RC_PROTOCOL
     \retval int  IR_SUCC             successfully
                   IR_FAIL       failed
 */
INT32 IRRX_InitMtkIr(void)
{
    INT32 i4Ret;
    PFN_IRRXCB_T pfnOld;

    IR_API_LOG_LEAST; 

    _u4CurrKey = BTN_NONE;
    _u4PrevKey = BTN_NONE;
    _fgRepeat = 0;

    IRRX_StopMtkIr();//stop first, for the 8520 boot up test, by msz00420
    i4Ret = IRHW_RxSetCallback(_IRRX_MtkIrCallback, &pfnOld);
    
	//LOG(12, "for compile warning : 0x%08x\n", _au4MtkCrystalKeyMap[1]);
	
    if (i4Ret != IR_SUCC)
    {
        return IR_FAIL;
    }

    #if (IRRX_RC_PROTOCOL == IRRX_RC_NEC )   
    //write 0x121, 0x32 0x201 to config register    
    i4Ret =   IRHW_RxInit(MTK_IRRX_CONFIG, MTK_IRRX_SAPERIOD, MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_RC6) 
    //write 0x135 0x2e 0x201 to config register
    i4Ret = IRHW_RxInit(IRRX_RC6_CONFIG, IRRX_SAPERIOD_RC6, MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_SIRC) 
    i4Ret = IRHW_RxInit(IRRX_SIRC_CONFIG,  IRRX_SAPERIOD_SIRC,  MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_JVC) 
    i4Ret = IRHW_RxInit(IRRX_JVC_CONFIG,  IRRX_SAPERIOD_JVC,  MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_PAN) 
    i4Ret = IRHW_RxInit(IRRX_PAN_CONFIG,  IRRX_SAPERIOD_PAN,  MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_RC5) 
    i4Ret = IRHW_RxInit(IRRX_RC5_CONFIG, IRRX_SAPERIOD_RC5, MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_RCA) 
    i4Ret = IRHW_RxInit(IRRX_RCA_CONFIG,  IRRX_SAPERIOD_RCA,  (MTK_IRRX_THRESHOLD+1));
    #else  //default is nec protol 
    
       i4Ret =   IRHW_RxInit(MTK_IRRX_CONFIG, MTK_IRRX_SAPERIOD, MTK_IRRX_THRESHOLD);
    #endif    
       
    if (i4Ret != IR_SUCC)
    {
        return IR_FAIL;
    }

  #if CONFIG_SUSPEND_TO_DRAM
    register_pm_ops(&irrx_pm_ops);
  #endif

    if (!_fgSemaInit)
    {
        i4Ret =
            x_sema_create(&_hSemaKey, X_SEMA_TYPE_BINARY, X_SEMA_STATE_LOCK);
        if (i4Ret != OSR_OK)
        {
            return IR_FAIL;
        }
        _fgSemaInit = TRUE;
    }

#ifdef CC_SBOX_HWCTRL
    /* GPIO must be initialized before IRRC */
    GPIO_Reg(61, GPIO_TYPE_INTR, _GPIO_IrKeyCallback);
    for (i4Ret = 122; i4Ret < 128; i4Ret++)
    {
        GPIO_Reg(i4Ret, GPIO_TYPE_INTR, _GPIO_IrKeyCallback);
    }
    /* Setup Power key wakeup. */
    i4Ret = 0x01;
    IRHW_PKey(&i4Ret);
#endif

#if (CONFIG_DRV_LINUX && CONFIG_ARM2_EJECT) 
    if(IRRX_FastEject_Init() != IR_SUCC)
    {
        return IR_FAIL;
    }
#endif

    return IR_SUCC;
}


#if(! CONFIG_DRV_LINUX )
INT32 i4IrUninit(void) 
#else
INT32 i4Ir_Uninit(UINT32 u4Case) 
#endif
{
    INT32 i4Ret;
    INT32 i4Error = 0;

#ifndef VFD_TOOL_CONFIG_MODE
  #if (CONFIG_CHIP_VER_CURR >= CONFIG_CHIP_VER_MT8530) 
   UINT32 u4KeyCodeM = 0, u4KeyCodeL = 0;
  #endif
#endif

  #if CONFIG_SUSPEND_TO_DRAM
    unregister_pm_ops(&irrx_pm_ops);
  #endif

    i4IrHWUninit();
    
    i4Ret = IRHW_RxSetCallback(NULL, NULL);

     if (i4Ret != IR_SUCC)
    {
         i4Error = IR_FAIL;
    }
    
    _fgSemaInit = FALSE;

    i4Ret =  x_sema_delete(_hSemaKey);
    
    if (i4Ret != OSR_OK)
    {
            i4Error = IR_FAIL;
    }
	
#ifndef VFD_TOOL_CONFIG_MODE
 #if (CONFIG_CHIP_VER_CURR >= CONFIG_CHIP_VER_MT8530) 
  #if (IRRX_RC_PROTOCOL == IRRX_RC_RC6 )  
    i4Ret = _IRRX_XferCrystalToRC6(BTN_POWER, &u4KeyCodeM, &u4KeyCodeL);
  #elif (IRRX_RC_PROTOCOL == IRRX_RC_SIRC)
    i4Ret = _IRRX_XferCrystalToSIRC(BTN_POWER, &u4KeyCodeM, &u4KeyCodeL);
  #elif (IRRX_RC_PROTOCOL == IRRX_RC_JVC)
    i4Ret = _IRRX_XferCrystalToJVC(BTN_POWER, &u4KeyCodeM, &u4KeyCodeL);
  #elif (IRRX_RC_PROTOCOL == IRRX_RC_PAN)
    i4Ret = _IRRX_XferCrystalToPAN(BTN_POWER, &u4KeyCodeM, &u4KeyCodeL);
  #elif (IRRX_RC_PROTOCOL == IRRX_RC_RC5 )  
    i4Ret = _IRRX_XferCrystalToRC5(BTN_POWER, &u4KeyCodeM, &u4KeyCodeL);
  #elif (IRRX_RC_PROTOCOL == IRRX_RC_RCA)
    i4Ret = _IRRX_XferCrystalToRCA(BTN_POWER, &u4KeyCodeM, &u4KeyCodeL);
  #else
    i4Ret = _IRRX_XferCrystalToMtk(BTN_POWER, &u4KeyCodeM, &u4KeyCodeL);
  #endif

    if (i4Ret != IR_SUCC)
    {
       i4Error = IR_FAIL;
    }
   
	i4Ret = _IRHW_WAKE_UP_ENABLE(0, u4KeyCodeM, u4KeyCodeL);
	if (i4Ret != IR_SUCC)
	{
	   i4Error = IR_FAIL;
	}
#endif

   #if 0	
	i4Ret = _IRHW_POWER_DOWN_ENABLE(0, u4KeyCodeM, u4KeyCodeL);
	if (i4Ret != IR_SUCC)
	{
	   i4Error = IR_FAIL;
	}
   #endif	
 #endif
   
    return  i4Error;
     
}

/*! \fn INT32 IRRX_StopMtkIr(void)
      \brief Stop IR module       
     \retval int  IR_SUCC             successfully
                   IR_FAIL       failed
 */
INT32 IRRX_StopMtkIr(void)
{
    INT32 i4Ret;

    IR_API_LOG_LEAST; 
    i4Ret = IRHW_RxStop();
    return i4Ret;
}


/*! \fn INT32 IRRX_ResetMtkIr(void)
      \brief Reset IR module       
     \retval int  IR_SUCC             successfully
                   IR_FAIL       failed
 */
INT32 IRRX_ResetMtkIr(void)
{
    INT32 i4Ret;

    IR_API_LOG_LEAST;

    i4Ret = IRRX_StopMtkIr();
    i4Ret |= IRRX_InitMtkIr();
    return i4Ret;
}
EXPORT_SYMBOL(IRRX_ResetMtkIr);

INT32 IRRX_Partial_ResetMtkIr(void)
{
    INT32 i4Ret;
 //   PFN_IRRXCB_T pfnOld;

//    IR_API_LOG_LEAST; 

//    _u4CurrKey = BTN_NONE;
//    _u4PrevKey = BTN_NONE;
//    _fgRepeat = 0;

    IRRX_StopMtkIr();//stop first, for the 8520 boot up test, by msz00420
    /*
    i4Ret = IRHW_RxSetCallback(_IRRX_MtkIrCallback, &pfnOld);
    
	//LOG(12, "for compile warning : 0x%08x\n", _au4MtkCrystalKeyMap[1]);
	
    if (i4Ret != IR_SUCC)
    {
        return IR_FAIL;
    }
*/
    #if (IRRX_RC_PROTOCOL == IRRX_RC_NEC )   
    //write 0x121, 0x32 0x201 to config register    
    i4Ret =   IRHW_RxInit(MTK_IRRX_CONFIG, MTK_IRRX_SAPERIOD, MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_RC6) 
    //write 0x135 0x2e 0x201 to config register
    i4Ret = IRHW_RxInit(IRRX_RC6_CONFIG, IRRX_SAPERIOD_RC6, MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_SIRC) 
    i4Ret = IRHW_RxInit(IRRX_SIRC_CONFIG,  IRRX_SAPERIOD_SIRC,  MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_JVC) 
    i4Ret = IRHW_RxInit(IRRX_JVC_CONFIG,  IRRX_SAPERIOD_JVC,  MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_PAN) 
    i4Ret = IRHW_RxInit(IRRX_PAN_CONFIG,  IRRX_SAPERIOD_PAN,  MTK_IRRX_THRESHOLD);
    #elif (IRRX_RC_PROTOCOL == IRRX_RC_RC5) 
    i4Ret = IRHW_RxInit(IRRX_RC5_CONFIG, IRRX_SAPERIOD_RC5, MTK_IRRX_THRESHOLD);
      #elif (IRRX_RC_PROTOCOL == IRRX_RC_RCA) 
    i4Ret = IRHW_RxInit(IRRX_RCA_CONFIG,  IRRX_SAPERIOD_RCA,  MTK_IRRX_THRESHOLD);
    #else  //default is nec protol 
    
       i4Ret =   IRHW_RxInit(MTK_IRRX_CONFIG, MTK_IRRX_SAPERIOD, MTK_IRRX_THRESHOLD);
    #endif    
       
    if (i4Ret != IR_SUCC)
    {
        return IR_FAIL;
    }
#if 0
  #if CONFIG_SUSPEND_TO_DRAM
    register_pm_ops(&irrx_pm_ops);
  #endif

    if (!_fgSemaInit)
    {
        i4Ret =
            x_sema_create(&_hSemaKey, X_SEMA_TYPE_BINARY, X_SEMA_STATE_LOCK);
        if (i4Ret != OSR_OK)
        {
            return IR_FAIL;
        }
        _fgSemaInit = TRUE;
    }

#ifdef CC_SBOX_HWCTRL
    /* GPIO must be initialized before IRRC */
    GPIO_Reg(61, GPIO_TYPE_INTR, _GPIO_IrKeyCallback);
    for (i4Ret = 122; i4Ret < 128; i4Ret++)
    {
        GPIO_Reg(i4Ret, GPIO_TYPE_INTR, _GPIO_IrKeyCallback);
    }
    /* Setup Power key wakeup. */
    i4Ret = 0x01;
    IRHW_PKey(&i4Ret);
#endif

#if (CONFIG_DRV_LINUX && CONFIG_ARM2_EJECT) 
    if(IRRX_FastEject_Init() != IR_SUCC)
    {
        return IR_FAIL;
    }
#endif
#endif
    return IR_SUCC;
}

/*! \fn INT32 IRRX_SendMtkIr(UINT32 u4Key)
      \brief      simulate a new key is pressed
     \retval INT32  IR_SUCC             successfully
                   IR_FAIL       failed
 */
INT32 IRRX_SendMtkIr(UINT32 u4Key)
{
    INT32 i4Ret;

    LOG(6, "%s add Key:0x%08x\n", __FUNCTION__, u4Key);
    _u4CurrKey = u4Key;
    if(_fgSemaInit == TRUE)
    {
    i4Ret = x_sema_unlock(_hSemaKey);
    if (i4Ret != OSR_OK)
    {
        LOG(1, "%s(%d) SemaKey is fault. i4Ret:%d\n", __FILE__, __LINE__,
            i4Ret);
        return IR_FAIL;
    }
    }
    else
    {
        return IR_FAIL;
    }
	
    return IR_SUCC;
}
EXPORT_SYMBOL(IRRX_SendMtkIr);


/*! \fn INT32 IRRX_PollMtkIr(UINT32 * pu4Key)
      \brief      fetch a key event
      \param    pu4Key: Point to a UINT32 data,  which is used to return the 
                    key value, it will be BTN_NONE if there is no key pressed.
     \retval INT32  IR_SUCC             successfully
                   IR_FAIL       failed
 */
INT32 IRRX_PollMtkIr(UINT32 * pu4Key)
{
    INT32 i4Ret;
    CRIT_STATE_T cState;
    static UINT32 ui4IrRxPollSpace = IRRX_POLL_SPACE_2ST;

    //LOG(10, ": %s\n", __FUNCTION__);

    if (pu4Key == NULL)
    {
        return IR_FAIL;
    }

    if(_fgSemaInit == FALSE)
    {
        return IR_FAIL;
    }
	
    i4Ret = x_sema_lock_timeout(_hSemaKey, ui4IrRxPollSpace);
    if (i4Ret == OSR_OK)
    {
        if (_u4CurrKey == BTN_KEY_REPEAT)
        {
            ui4IrRxPollSpace = IRRX_POLL_SPACE_2ST;
        }
        else
        {
            ui4IrRxPollSpace = IRRX_POLL_SPACE_1ST;
        }
        cState = x_crit_start();
        *pu4Key = _u4CurrKey;
        _u4CurrKey = BTN_NONE;
        x_crit_end(cState);        
    }
    else if (i4Ret == OSR_TIMEOUT)
    {
        _u4PrevKey = BTN_NONE;
        *pu4Key = BTN_NONE;
    }
    else
    {
        ASSERT((i4Ret == OSR_OK) || (i4Ret == OSR_TIMEOUT));
        _u4PrevKey = BTN_NONE;
        *pu4Key = BTN_NONE;
        return IR_FAIL;
    }

    if (*pu4Key == BTN_NONE)
    {
        LOG(11, "%s return Key:0x%08x\n", __FUNCTION__, *pu4Key);
    }
    else
    {
        LOG(6, "%s return Key:0x%08x\n", __FUNCTION__, *pu4Key);
    }
    return IR_SUCC;
}
EXPORT_SYMBOL(IRRX_PollMtkIr);


/*! \fn INT32 IRRX_QuerySetRepeat(const INT32 * pi4Data)
      \brief      set _fgRepeat
      \param    pi4Data: Point to a UINT32 data,  which is used to set the 
                    _fgRepeat.
     \retval INT32  the new _fgRepeat value if pi4Data isn't null or the 
     current  _fgRepeat value if pi4Data is NULL.
 */
INT32 IRRX_QuerySetRepeat(const INT32 * pi4Data)
{

    IR_API_LOG_LEAST; if (pi4Data != NULL)
    {
        _fgRepeat = *pi4Data;
    }
    return _fgRepeat;
}
EXPORT_SYMBOL(IRRX_QuerySetRepeat);


/*! \fn UINT32 IRRX_QuerySet1stPulse(const UINT32 * pu4Data)
      \brief      set _u4_1stPulse or get the value of _u4_1stPulse
      \param    pi4Data: Point to a UINT32 data,  which is used to set the 
                    _u4_1stPulse.
     \retval INT32  the new_u4_1stPulse value if pi4Data isn't null or the 
     current _u4_1stPulse value if pi4Data is NULL.
 */
UINT32 IRRX_QuerySet1stPulse(const UINT32 * pu4Data)
{
  #if (IRRX_RC_PROTOCOL == IRRX_RC_NEC)
    IR_API_LOG_LEAST; if (pu4Data != NULL)
    {
        _u4_1stPulse = *pu4Data;
    }
    return _u4_1stPulse;
  #else
    return 0;
  #endif	
}


/*! \fn UINT32 IRRX_QuerySet2ndtPulse(const UINT32 * pu4Data)
      \brief      set _u4_2ndPulse  or get the value of _u4_2ndPulse
      \param    pi4Data: Point to a UINT32 data,  which is used to set the 
                    _u4_2ndPulse .
     \retval INT32  the new_u4_2ndPulse  value if pi4Data isn't null or the 
     current _u4_2ndPulse  value if pi4Data is NULL.
 */
UINT32 IRRX_QuerySet2ndPulse(const UINT32 * pu4Data)
{
  #if (IRRX_RC_PROTOCOL == IRRX_RC_NEC)
    IR_API_LOG_LEAST; if (pu4Data != NULL)
    {
        _u4_2ndPulse = *pu4Data;
    }
    return _u4_2ndPulse;
  #else
    return 0;
  #endif

}


/*! \fn UINT32 IRRX_QuerySet3rdtPulse(const UINT32 * pu4Data)
      \brief      set _u4_3rdPulse  or get the value of _u4_3rdPulse
      \param    pi4Data: Point to a UINT32 data,  which is used to set the 
                    _u4_2ndPulse .
     \retval INT32  the new_u4_3rdPulse  value if pi4Data isn't null or the 
     current _u4_2ndPulse  value if pi4Data is NULL.
 */
UINT32 IRRX_QuerySet3rdPulse(const UINT32 * pu4Data)
{
  #if (IRRX_RC_PROTOCOL == IRRX_RC_NEC)
    IR_API_LOG_LEAST; if (pu4Data != NULL)
    {
        _u4_3rdPulse = *pu4Data;
    }
    return _u4_3rdPulse;
  #else
    return 0;
  #endif

}


/*! \fn UINT32 IRRX_QuerySetGroupId(const UINT32 * pu4Data)
      \brief      set _u4GroupId  or get the value of _u4GroupId
      \param    pi4Data: Point to a UINT32 data,  which is used to set the 
                    _u4GroupId .
     \retval INT32  the new _u4GroupId  value if pi4Data isn't null or the 
     current _u4GroupId  value if pi4Data is NULL.
 */
UINT32 IRRX_QuerySetGroupId(const UINT32 * pu4Data)
{
  #if (IRRX_RC_PROTOCOL == IRRX_RC_NEC)
    IR_API_LOG_LEAST; if (pu4Data != NULL)
    {
        _u4GroupId = *pu4Data;
    }
    return _u4GroupId;
  #else
    return 0;
  #endif
}
EXPORT_SYMBOL(IRRX_QuerySetGroupId);


/*! \fn UINT32 IRRX_QuerySetRepeatTime(const UINT32 * pu4Data)
      \brief      set _u4IrRxTimeSlice  or get the value of _u4IrRxTimeSlice
      \param    pi4Data: Point to a UINT32 data,  which is used to set the 
                    _u4IrRxTimeSliced .
     \retval INT32  the new _u4IrRxTimeSlice value if pi4Data isn't null or the 
     current _u4IrRxTimeSlice  value if pi4Data is NULL.
 */
UINT32 IRRX_QuerySetRepeatTime(const UINT32 * pu4Data)
{
    IR_API_LOG_LEAST; if (pu4Data != NULL)
    {
        _u4IrRxTimeSlice = *pu4Data;
    }
    return _u4IrRxTimeSlice;
}
EXPORT_SYMBOL(IRRX_QuerySetRepeatTime);

/******************************************************************************
********** Diagnostic function
******************************************************************************/
REG_TEST_T arIRRgtList[] = {
	{0x0200, eRD_ONLY, 4, 0xffffff3f, 0, 0},
	{0x0204, eRD_ONLY, 4, 0xffffffff, 0, 0},
	{0x0208, eRD_ONLY, 4, 0x00ffffff, 0, 0},
	{0x020c, eRD_WR, 2, 0x0ff7, 1, 0},
	{0x0210, eRD_WR, 2, 0x00ff, 1, 0xff},
	{0x0214, eRD_WR, 2, 0x037f, 1, 0},
      #if (CONFIG_CHIP_VER_CURR == CONFIG_CHIP_VER_MT8520)   
        // IR TX
        {0x0224, eRD_WR, 4, 0xffffffff, 1, 0},
        {0x0228, eRD_WR, 4, 0xffffffff, 1, 0},
        {0x022c, eRD_WR, 4, 0xffffffff, 1, 0},
        {0x0230, eRD_WR, 4, 0xffffffff, 1, 0x10083b4c},
        {0x0234, eRD_WR, 4, 0xffffffff, 1, 0x00e602b2},
        // IR TX have STRT at bit 0 of 0x8220, it will make data register halting for a moment. */
        {0x0220, eRD_WR, 2, 0x7ffe, 1, 0},// we don't have to test bit 0, it's trigger bit.      
      #endif
	{0xffff, eNO_TYPE, -1, 0, 0, 0}
};

#define DIAG_RETRY  10

INT32 IR_Diag()
{
    INT32 i4Ret, i4Val, i4Try;
    UINT32 u4Key ;

    i4Val = 0;
    i4Ret = 0;
    // i4Ret = UTIL_RegDefChk(IR_BASE, arIRRgtList);
    // i4Val |= i4Ret;
    LOG(0, "Register default value check .............. %s\n",
           (i4Ret ? "FAIL" : "PASS"));
    i4Ret = UTIL_RegRWTest(IR_BASE, arIRRgtList);
    i4Val |= i4Ret;
    LOG(0, "Register read/write test .................. %s\n",
           (i4Ret ? "FAIL" : "PASS"));
    i4Ret = UTIL_AllSpaceRWTest(IR_BASE, IR_REG_LENGTH);
    i4Val |= i4Ret;
    LOG(0, "Memory space read/write test .............. %s\n",
           (i4Ret ? "FAIL" : "PASS"));

    VERIFY(IRRX_InitMtkIr() == IR_SUCC);
    LOG(0, 
        "\n\nPlease use MTK TV remote controller, and follow the required key to press button\n");

    /******************************************** Power Key **********************************/
    LOG(0, "[ Power Key ]\n");
    i4Try = 0;
    do
    {
        i4Ret = IRRX_PollMtkIr(&u4Key);
        if (u4Key != BTN_NONE)
        {
            i4Try++;
        }
    }
    while ((i4Ret == IR_SUCC) && (u4Key != BTN_POWER)
           && (i4Try < DIAG_RETRY));
    LOG(0, "[ Power Key ] ............................. %s\n",
           (((i4Ret == IR_SUCC) && (u4Key == BTN_POWER)) ? "PASS" : "FAIL"));
    i4Val |= (INT32) ! (u4Key == BTN_POWER);
    do
    {
        i4Ret = IRRX_PollMtkIr(&u4Key);
    }
    while ((i4Ret == IR_SUCC) && (u4Key != BTN_NONE));

    /******************************************** Dig 1 Key **********************************/
    LOG(0, "[ Dig 1 Key ]\n");
    i4Try = 0;
    do
    {
        i4Ret = IRRX_PollMtkIr(&u4Key);
        if (u4Key != BTN_NONE)
        {
            i4Try++;
        }
    }
    while ((i4Ret == IR_SUCC) && (u4Key != BTN_DIGIT_1)
           && (i4Try < DIAG_RETRY));
    LOG(0, "[ Dig 1 Key ] ............................. %s\n",
           (((i4Ret == IR_SUCC)
             && (u4Key == BTN_DIGIT_1)) ? "PASS" : "FAIL"));
    i4Val |= (INT32) ! (u4Key == BTN_DIGIT_1);
    do
    {
        i4Ret = IRRX_PollMtkIr(&u4Key);
    }
    while ((i4Ret == IR_SUCC) && (u4Key != BTN_NONE));

    /******************************************** Dig 2 Key **********************************/
    LOG(0, "[ Dig 2 Key ]\n");
    i4Try = 0;
    do
    {
        i4Ret = IRRX_PollMtkIr(&u4Key);
        if (u4Key != BTN_NONE)
        {
            i4Try++;
        }
    }
    while ((i4Ret == IR_SUCC) && (u4Key != BTN_DIGIT_2)
           && (i4Try < DIAG_RETRY));
    LOG(0, "[ Dig 2 Key ] ............................. %s\n",
           (((i4Ret == IR_SUCC)
             && (u4Key == BTN_DIGIT_2)) ? "PASS" : "FAIL"));
    i4Val |= (INT32) ! (u4Key == BTN_DIGIT_2);
    do
    {
        i4Ret = IRRX_PollMtkIr(&u4Key);
    }
    while ((i4Ret == IR_SUCC) && (u4Key != BTN_NONE));

    return i4Val;
}


/*!\fn INT32 IR_Status_WD(void);
        brief LOG(0,  the value of IR config regsiters. 	
*/
INT32 IR_Status_WD(void)
{
    INT32 i4CfgHigh, i4CfgMid, i4CfgLow;
    UINT32 u4CntHigh, u4CntMid, u4CntLow;

    IRHW_RxRdConf(&i4CfgHigh, &i4CfgMid, &i4CfgLow);

    LOG(0, 
        "[IR Status:] value of High Mid Low config resgisters : 0x%08x  0x%08x  0x%08x\n",
         i4CfgHigh, i4CfgMid, i4CfgLow);

    u4CntHigh = IR_READ32(IRRX_COUNT_HIGH_REG);
    u4CntMid = IR_READ32(IRRX_COUNT_MID_REG);
    u4CntLow = IR_READ32(IRRX_COUNT_LOW_REG);
    LOG(0, "\t value of Count Registers :0x%08x 0x%08x%08x\n", u4CntHigh,
           u4CntMid, u4CntLow);
    LOG(0, "\t Prev key: 0x%08x		current key: 0x%08x\n", _u4PrevKey,
           _u4CurrKey);
    LOG(0, "\tCurrent Settins: \n");
  #if (IRRX_RC_PROTOCOL == IRRX_RC_NEC) 	
    LOG(0, "\tGroupId : 0x%08x\n", _u4GroupId);
    LOG(0, " \t1st plus : 0x%08x\n", _u4_1stPulse);
    LOG(0, " \t2nd pus : 0x%08x\n", _u4_2ndPulse);
    LOG(0, " \t3rd pus: 0x%08x\n", _u4_3rdPulse);
  #endif	
    LOG(0, " \tfgRepeat: %d\n", _fgRepeat);
    LOG(0, "\t_u4IrRxTimeSlice: %d \n", _u4IrRxTimeSlice);

    return IR_SUCC;
}

#if 1//SUPPORT_2K12_IR_CHILD_LOCK
static BOOL _fgIREnableChildLock = FALSE;

void IRRX_SetEnable_ChildLock(BOOL b_fgEnable)
{
	_fgIREnableChildLock = b_fgEnable;
	LOG(1, "%s(%d) IRRX_SetEnable_ChildLock,_fgIREnableChildLock=%d.\n", __FILE__, __LINE__,_fgIREnableChildLock);
}

BOOL IRRX_GetEnable_ChildLock(void)
{
	return _fgIREnableChildLock;
}

EXPORT_SYMBOL(IRRX_SetEnable_ChildLock);
#endif 
