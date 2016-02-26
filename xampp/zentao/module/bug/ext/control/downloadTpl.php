<?php
/**
 * coship start
 * 2013-12-20 add file by fujia
 * bugÄ£°åÏÂÔØ
*/
class bug extends control
{
    public function downloadTpl()
    {
        if(file_exists($this->config->bug->filePath))
        {
            $fileData = file_get_contents($this->config->bug->filePath);
            ob_clean();    // clean the ob content to make sure no space or utf-8 bom output.

            /* Set the downloading cookie, thus the export form page can use it to judge whether to close the window or not. */
            setcookie('downloading', 1);

            /* urlencode the filename for ie. */
            $fileName = $this->config->bug->fileName . '.' . $this->config->bug->fileExtension;
            if(strpos($this->server->http_user_agent, 'MSIE') !== false) $fileName = urlencode($fileName);

            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            die($fileData);
        }
        else
        {
            $this->app->error("The file you visit not found.", __FILE__, __LINE__, true);
        }
    }
}
/* coship end */