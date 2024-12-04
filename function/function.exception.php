<?

function callException($msg, $type, $hide=false) {
    switch ($type) {
           case 0: $class   ='alert-green';
                   break;
           case 1: $class   ='alert-danger';
                   break;
           case 2: $class   ='alert-warning text-white';
                   break;
           default:$class   ='alert-primary';
                   break;
    }
    if($hide) $class .=' alert';
    return "
        <div class='rounded p-2 ".$class." alert-dismissable' role='alert'>
        <div>
            ".trim($msg)."
        </div>
        </div>";
}
