<?php

namespace App\Actions\Mail;

use App\Data\Filter\FilterData;
use App\Data\MessageSetup\MessageSetupData;
use Illuminate\Http\Request;

class MailSetup
{
    protected $filters;
    function __construct($filters=[])
    {
        $this->filters = $filters;
    }
    public function setToFile()
    {
        // $filters = (new FilterData($this->request))->getData();
        $messageSetupData = new MessageSetupData($this->filters);
      
        $data['messageSetup'] = $messageSetupData->getData();
       
        $data['title'] = $messageSetupData->getTitle();
        $data['client'] = "Tejendra Dangaura";
        $link = resource_path().'\views\admin\message-setup\test.blade.php';
       
        // dd(str_replace("&lt;?php", "<?php", $data));
        $fp = fopen($link, 'w');
        fwrite($fp, $data['messageSetup']->message);
        fclose($fp);
                
        $file_contents = file_get_contents($link);
        $file_contents = str_replace("&lt;?php", "<?php", $file_contents);
        $file_contents = str_replace("?&gt;", "?>", $file_contents);
        file_put_contents($link, $file_contents);
    }

    

    
}