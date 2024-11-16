<?php
namespace Learning\Diagnostic;
use Bitrix\Main\Diag\FileExceptionHandlerLog;
use Bitrix\Main\Diag\ExceptionHandlerFormatter;

class LearningFileExceptionHandlerLog extends FileExceptionHandlerLog{
    private $level;
    
    public function write($exception, $logType){
        $logLevel = static::logTypeToLevel($logType);
        $text = ExceptionHandlerFormatter::format($exception, false, (int)$logLevel);
        
        $message = "\n----------------------------------\n";
        $message .= "OTUS ".date('l jS \of F Y h:i:s A') . "\n";
        $lines = preg_split('/\\r\\n?|\\n/', $text);
        
        foreach($lines as $key=>$line){
            if(!empty($line) && $line != '----------'){
                $message .= "OTUS ".$line;
                if($key != count($lines)-1){
                    $message .= "\r";
                }
            }
        }
        $message .= "\n----------------------------------\n";
		
        if(!DEBUG_FILE_NAME){
            return false;
        }
        
        file_put_contents(DEBUG_FILE_NAME, $message, FILE_APPEND);
    }
}

