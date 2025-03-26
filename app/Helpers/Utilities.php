<?php

namespace App\Helpers;

class   Utilities
{
    public static function button(
        $href,
        $icon,
        $color = "primary",
        $title = "",
    ) {
        $text = <<<IDENTIFIER
        <a href="$href" class="btn btn-sm btn-$color me-1" title='$title'>
        <i data-feather='$icon'></i>
        </a>
        IDENTIFIER;

        return $text;
    }

    public static function status($status, $id)
    {
        if($status == '1'){
            $status1 = "<span class='badge badge-status bg-light-success' toggle-id=$id>Active</span>" ;
            return $status1;
        }elseif($status == '0'){
            $status2 =  "<span class='badge badge-status bg-light-danger' toggle-id=$id>Deactivated</span>";
            return $status2;
        }else if($status == '2'){
            $status3 = "<span class='badge badge-status bg-light-danger' toggle-id=$id>Blocked</span>";
            return $status3;
        }else{
            $status4 = "<span class='badge badge-status bg-light-danger' toggle-id=$id>Inactive</span>";
            return $status4;
        }             
    }
    



    public static function image($image)
    {
        $text = <<<IDENTIFIER
        <img class="img-responsive" height="100px" width="100px"  src="$image"/>
        IDENTIFIER;

        return $text;
    }
    
    public static function approve($id){
        $text = <<<IDENTIFIER
        <input type="checkbox" class="check-to-all" name="foo" data-id="$id" id="order"/>
        IDENTIFIER;

        return $text;
    }

    public static function delete($id, $href)
    {
        $csrf = csrf_field();

        $text = <<<DELETERECORD
            <div class="disabled-backdrop-ex">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#backdropId-$id">
            <i data-feather='trash'></i>
            </button>
            <!-- Modal -->
            <div class="modal fade text-start" id="backdropId-$id" tabindex="-1" aria-labelledby="myModalLabel4" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <form action="$href" method="POST">
                    $csrf
                    <input type='hidden' name="_method" value="DELETE"/>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel4">Delete Record</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                               This action is irreversiable. Are you sure??
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-warning" data-bs-dismiss="modal">cancel</button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">delete</button>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
        DELETERECORD;
        return $text;
    }


    
}
