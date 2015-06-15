<?php

class BannersController extends BaseController
{
    private $scode;
    private $images;

    public function __construct(Scode $scode, Images $images)
    {
        $this->scode = $scode;
        $this->images = $images;
    }

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function getIndex()
    {
        $data = Input::all();

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin SiamiTs :: Members');
        $theme->setDescription('Members description');
        $theme->share('user', $this->user);

        $page    = array_get($data, 'page', '1');
        $perpage = array_get($data, 'perpage', '10');
        $order   = array_get($data, 'order', 'id');
        $sort    = array_get($data, 'sort', 'desc');

        $parameters = array(
            'page'    => $page,
            'perpage' => $perpage,
            'order'   => $order,
            'sort'    => $sort,
        );

        if ($s = array_get($data, 's', false)) {
            $parameters['s'] = $s;
        }

        $client = new Client(Config::get('url.siamits-api'));
        $results = $client->get('banners', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Data not found');

            if ($status_code != '1004') {
                return Redirect::to('banners')->with('error', $message);
            }
        }

        if (isset($_GET['sdebug'])) {
            alert($results);
            die();
        }

        $entries = array_get($results, 'data.record', array());

        $table_title = array(
            'id'           => array('ID ', 1),
            'position'     => array('Position', 1),
            'title'        => array('Title', 1),
            'subtitle'     => array('Subtitle', 1),
            // 'button'       => array('Button', 1),
            // 'button_title' => array('Button_title', 1),
            // 'button_url'   => array('Button_url', 1),
            'type'         => array('Type', 1),
            'images'       => array('Image', 0),
            'status'       => array('Status', 1),
            'manage'       => array('Manage', 0),
        ); 

        $view = array(
            'num_rows'    => count($entries),
            'data'        => $entries,
            'param'       => $parameters,
            'table_title' => $table_title,
        );

        //Pagination
        if ($pagination = self::getDataArray($results, 'data.pagination')) {
            $view['pagination'] = self::getPaginationsMake($pagination, $entries);
        }

        $script = $theme->scopeWithLayout('banners.jscript_list', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('banners.list', $view)->render();
    }

    public function getAdd()
    {
        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin SiamiTs :: Add Banners');
        $theme->setDescription('Add Banners description');
        $theme->share('user', $this->user);

        $parameters = array(
            'user_id' => '1',
            'perpage'   => '100',
            'order'     => 'id',
            'sort'      => 'desc'
        );

        $client = new Client(Config::get('url.siamits-api'));
        $results = $client->get('banners', $parameters);
        $results = json_decode($results, true);

        $id_max = array_get($results, 'data.record.0.id', '0');

        $view = array(
            'id_max' => $id_max
        );

        $script = $theme->scopeWithLayout('banners.jscript_add', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('banners.add', $view)->render();
    }

    public function postAdd()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'images'  => 'required',
            'user_id' => 'required',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = array(
                'message' => $validator->messages()->first(),
            );

            return Redirect::to('banners/add')->with('error', $message);
        }

        // Add banner
        $parameters = array(
            'user_id'    => array_get($data, 'user_id', ''),
            'title'      => array_get($data, 'title', ''),
            'subtitle'   => array_get($data, 'subtitle', ''),
            'button'     => array_get($data, 'button', ''),
            'button_url' => array_get($data, 'button_url', ''),
            'images'     => array_get($data, 'images', ''),
            'position'   => array_get($data, 'positon', '0'),
            'type'       => array_get($data, 'user_id', '1'),
            'status'     => array_get($data, 'user_id', '1'),
        );

        $client = new Client(Config::get('url.siamits-api'));
        $results = $client->post('banners', $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not created banners');

            return Redirect::to('banners/add')->with('error', $message);
        }

        $message = 'You successfully created';
        return Redirect::to('banners')->with('success', $message);
    }

    public function getEdit($id)
    {
        $data = Input::all();
        $data['id'] = $id;

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin SiamiTs :: Edit Banners');
        $theme->setDescription('Edit Banners description');
        $theme->share('user', $this->user);

        $parameters = array(
            'user_id' => '1'
        );

        $client = new Client(Config::get('url.siamits-api'));
        $results = $client->get('banners/'.$id, $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array(
                'message' => array_get($results, 'status_txt', 'Can not created banners'),
            );

            return Redirect::to('banners')->withErrors($message);
        }

        $banners = array_get($results, 'data.record', array());
        $id_max  = array_get($banners, 'id', '0');

        $view = array(
            'id_max'  => $id_max,
            'banners' => $banners,
        );

        $script = $theme->scopeWithLayout('banners.jscript_edit', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('banners.edit', $view)->render();
    }

    public function postEdit()
    {
        $data = Input::all();
        $data['user_id'] = '1';

        $rules = array(
            'action' => 'required',
        );

        $referer = array_get($data, 'referer', 'members');
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to($referer)->with('error', $message);
        }

        $action = array_get($data, 'action', null);

        // Delete
        if ($action == 'delete') {
            // Validator request
            $rules = array(
                'id'        => 'required',
                'user_id' => 'required',
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to($referer)->with('error', $message);
            }

            $id        = array_get($data, 'id', 0);
            $user_id = array_get($data, 'user_id', 0);

            $delete_file  = true;
            if ($fileName = array_get($data, 'image_name', false)) {
                $path     = '../res/public/uploads/'.$user_id; // upload path
                $old_file = $path.'/'.$fileName;

                // Delete old image
                //$delete_file = File::delete($old_file);
                $delete_file = $this->images->deleteFileAll($path, $name);
            }

            if ($delete_file) {
                // Delete banners
                $parameters = array(
                    'id'        => $id,
                    'user_id' => $user_id,
                );

                $client = new Client(Config::get('url.siamits-api'));
                $results = $client->delete('banners/'.$id, $parameters);
                $results = json_decode($results, true);

                if (array_get($results, 'status_code', false) != '0') {
                    $message = array(
                        'message' => array_get($results, 'status_txt', 'Can not delete banners'),
                    );

                    return Redirect::to('banners')->withErrors($message);
                }
            }

            $message = array(
                'message' => 'You successfully delete',
            );

        // Order
        } else if ($action == 'order') {
            // Validator request
            $rules = array(
                'id_sel'    => 'required',
                'user_id' => 'required',
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = array(
                    'message' => $validator->messages()->first(),
                );

                return Redirect::to('banners')->withErrors($message);
            }

            $user_id = array_get($data, 'user_id', 0);

            if ($id_sel = array_get($data, 'id_sel', false)) {
                $i = 1;
                foreach ($id_sel as $value) {
                    $id = $value;
                    $parameters2 = array(
                        'user_id' => $user_id,
                        'position'  => $i,
                    );

                    $client = new Client(Config::get('url.siamits-api'));
                    $results = $client->put('banners/'.$id, $parameters2);
                    $results = json_decode($results, true);

                    $i++;
                }
            }

            if (array_get($results, 'status_code', false) != '0') {
                $message = array(
                    'message' => array_get($results, 'status_txt', 'Can not order banners'),
                );

                return Redirect::to('banners')->withErrors($message);
            }

            $message = array(
                'message' => 'You successfully order',
            );

        // Edit
        } else {
            // Validator request
            $rules = array();
            if (!isset($data['image_old'])) {
                $rules = array(
                    'id'     => 'required',
                    'id_max' => 'required',
                    'image'  => 'required',
                );
            }

            $id = array_get($data, 'id', 0);

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = array(
                    'message' => $validator->messages()->first(),
                );

                return Redirect::to('banners')->withErrors($message);
            }

            $fileName = array_get($data, 'image_old', false);
            if (array_get($data, 'image', false)) {
                $cate            = 'banners';
                $user_id       = array_get($data, 'user_id', 0);
                $image           = array_get($data, 'image', null);
                $destinationPath = 'public/uploads/'.$user_id.'/'.$cate; // upload path
                $old_file = $destinationPath.'/'.$fileName;

                // Delete old image
                $delete_file = File::delete($old_file);

                // Upload image
                $random          = rand(0, 9);
                $datetime        = date("YmdHis");
                $image_code      = $user_id.$cate.$datetime.$random;
                $image_code      = base64_encode($image_code);
                $extension       = $image->getClientOriginalExtension(); // getting image extension
                $fileName        = $image_code . '.' . $extension; // renameing image
                $upload_image    = $image->move($destinationPath, $fileName); // uploading file to given path
                
                if (!isset($upload_image)) {
                    $message = array(
                        'message' => 'Can not upload image',
                    );

                    return Redirect::to('banners')->withErrors($message);
                }
            }
       
            $parameters = array(
                'user_id'    => $data['user_id'],
                'title'        => (isset($data['title'])?$data['title']:''),
                'subtitle'     => (isset($data['subtitle'])?$data['subtitle']:''),
                'button'       => (isset($data['button'])?$data['button']:'0'),
                'button_title' => (isset($data['button_title'])?$data['button_title']:''),
                'button_url'   => (isset($data['button_url'])?$data['button_url']:''),
                'image'        => $fileName,
                'position'     => (isset($data['position'])?$data['position']:'0'),
                'type'         => (isset($data['type'])?$data['type']:'1'),
                'status'       => (isset($data['status'])?$data['status']:'0')
            );

            $client = new Client(Config::get('url.siamits-api'));
            $results = $client->put('banners/'.$id, $parameters);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                $message = array(
                    'message' => array_get($results, 'status_txt', 'Can not edit banners'),
                );

                return Redirect::to('banners')->withErrors($message);
            }

            $message = array(
                'message' => 'You successfully edit',
            );
        }

        return Redirect::to('banners')->withSuccess($message);
    }

    private function getPaginationsMake($pagination, $record)
    {
        $total = array_get($pagination, 'total', 0);
        $limit = array_get($pagination, 'perpage', 0);
        $paginations = Paginator::make($record, $total, $limit);
        return isset($paginations) ? $paginations : '';
    }

    private function getDataArray($data, $key)
    {
        return array_get($data, $key, false);
    }
}
