<?php
if (!empty($row)) {

    $postdata = $req->post();

    $csrf = csrf_verify($postdata);
    if ($csrf) {

        if (user_can('delete_social_link')) {
            $image = new \Core\Image;
            $social->delete($row->id);

            if (file_exists($row->image));
                unlink($row->image);

            if (file_exists($image->get_thumbnail($row->image)));
                unlink($image->get_thumbnail($row->image));


            message_success("Record deleted successfully!");
            redirect($admin_route . '/' . $plugin_route);
        }
    }
    $social->errors['email'] = "Form expired!";

    set_value('errors', $social->errors);
} else {
    message_fail("Record not found!");
}


