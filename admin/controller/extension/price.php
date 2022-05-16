<?php
if (function_exists("set_time_limit")) {
    if (is_callable("set_time_limit")) {
        set_time_limit( 0 );
    }
}

class ControllerExtensionPrice extends Controller {
    private $error = array();
    private $pretype = '';
    private $type = 'extension';
    private $name = 'price';
    private $log_file_name = 'change_price.log';
    private $common = array();
    private $methods = array(
        'price', 'option', 'special_add', 'special', 'delete_special', 'discount_add', 'discount', 'delete_discount'
    );
    private $version = '2.2';
    private $token = 'user_token';
    private $tpl = '';
    private $ssl = true;


    public function error() {
        if (function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
        }

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version);

/*        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['text_notice'] = $this->language->get('error_file_get_contents');

        $data['local_title'] = $this->language->get('error_title');*/

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            
        );

        $data['action'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('extension/' . $this->type, 'user_token=' . $this->session->data['user_token'], true);


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name . '_error' . $this->tpl, $data));
    }


    public function error2() {
        if (class_exists('Louise170') and method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
        }

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version);

        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['text_notice'] = $this->language->get('error_louise170');

        $data['local_title'] = $this->language->get('error_title_lib');

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),

        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),

        );

        $data['action'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('extension/' . $this->type, 'user_token=' . $this->session->data['user_token'], true);


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name . '_error' . $this->tpl, $data));
    }


    public function index() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version);

        //$data['heading_title'] = $this->language->get('heading_title').' '.$this->version;

        if (isset($this->request->post[$this->name.'_license'])) {
            $data[$this->name.'_license'] = $this->request->post[$this->name.'_license'];
        }
        elseif ($this->config->get($this->name.'_license')) {
            $data[$this->name.'_license'] = $this->config->get($this->name.'_license');
        }
        else {
            $data[$this->name.'_license'] = '';
        }

        $this->load->model($this->type . '/' . $this->name);

        if ($this->model_extension_price->isCurrencyPlusExists()) {
            $this->methods[] = 'base_currency_code';
            $this->methods[] = 'base_price';
            $this->methods[] = 'base_option';
            $this->methods[] = 'special_base_add';
            $this->methods[] = 'discount_base_add';
        }

        if ($this->model_extension_price->isExtraChargeExists()) {
            $this->methods[] = 'extra_charge';
        }

        if ($this->model_extension_price->isOptionToProductExists()) {
            $this->methods[] = 'option_to_product';

            if ($this->model_extension_price->isCurrencyPlusExists()) {
                $this->methods[] = 'base_option_to_product';
            }
        }

        foreach ($this->methods as $method) {
            $data['methods'][$method]['title'] = $this->language->get('text_'.$method);
            $data['methods'][$method]['action'] = $this->url->link($this->type . '/' . $this->name.'/'.$method, 'user_token=' . $this->session->data['user_token'], true);
        }

        $data['clear'] = $this->url->link('extension/price/clear', 'user_token=' . $this->session->data['user_token'], true);

        $file = DIR_LOGS . $this->log_file_name;

        if (file_exists($file)) {
            $logs_size = filesize($file);

            if ($logs_size > 6291456) {
                $data['error_warning'] = sprintf($this->language->get('error_log_size'), round(($logs_size / 1048576), 2));
                $data['log'] = '';
            }
            else {
                $data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
            }
        }
        else {
            $data['log'] = '';
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validateLicense()) {
                $this->session->data['success'] = $this->language->get('text_success_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name . $this->tpl, $data));
    }


    public function price() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'price';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->changeProductPrice($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');


        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name . '_' . $this->local_name . $this->tpl, $data));
    }


    public function option() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'option';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        $filter_data = array(
            'sort'  => 'od.name',
            'order' => 'ASC'
        );

        $data['options'] = $this->model_extension_price->getOptions($filter_data);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->changeProductOptionPrice($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'.$this->local_name . $this->tpl, $data));
    }


    public function special_add() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'special_add';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->addProductSpecial($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'.$this->local_name . $this->tpl, $data));
    }


    public function special() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'special';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->changeProductSpecial($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'.$this->local_name . $this->tpl, $data));
    }


    public function delete_special() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'delete_special';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->deleteProductSpecial($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'.$this->local_name . $this->tpl, $data));
    }


    public function discount_add() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'discount_add';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->addProductDiscount($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'.$this->local_name . $this->tpl, $data));
    }


    public function discount() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'discount';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->changeProductDiscount($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'.$this->local_name . $this->tpl, $data));
    }


    public function delete_discount() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'delete_discount';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->deleteProductDiscount($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'.$this->local_name . $this->tpl, $data));
    }


    public function base_currency_code() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'base_currency_code';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (!$this->model_extension_price->isCurrencyPlusExists()) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->changeProductBaseCurrencyCode($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'.$this->local_name . $this->tpl, $data));
    }


    public function base_price() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'base_price';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (!$this->model_extension_price->isCurrencyPlusExists()) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->changeProductBasePrice($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'.$this->local_name . $this->tpl, $data));
    }


    public function base_option() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'base_option';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        $filter_data = array(
            'sort'  => 'od.name',
            'order' => 'ASC'
        );

        $data['options'] = $this->model_extension_price->getOptions($filter_data);

        if (!$this->model_extension_price->isCurrencyPlusExists()) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->changeProductOptionBasePrice($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'.$this->local_name . $this->tpl, $data));
    }


    public function special_base_add() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'special_base_add';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (!$this->model_extension_price->isCurrencyPlusExists()) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->addProductSpecialBase($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true)
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'. $this->local_name . $this->tpl, $data));
    }


    public function discount_base_add() {
        $this->registry->set('louise170', new Louise170($this->registry));

        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->local_name = 'discount_base_add';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (!$this->model_extension_price->isCurrencyPlusExists()) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->addProductDiscountBase($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true)
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->name.'_'. $this->local_name . $this->tpl, $data));
    }


    public function extra_charge() {
        $this->registry->set('louise170', new Louise170($this->registry));
        
        if (!function_exists("file_get_contents")) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (version_compare(VERSION, '3', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
        }

        if (!class_exists('Louise170') or !method_exists($this->louise170, 'check_license_adminka_276000') ) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name . '/error2', 'user_token=' . $this->session->data['user_token'], true));
        }


        $this->local_name = 'extra_charge';

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['name'] = $this->name;

        $this->document->setTitle($this->language->get('heading_title').' '.$this->version.' - '.$this->language->get('text_'.$this->local_name));
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
        $data['local_title'] = $this->language->get('text_'.$this->local_name);

        $new_data = $this->setModels();
        $data = array_merge($data, $new_data);

        if (!$this->model_extension_price->isExtraChargeExists()) {
            $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
        }

        $data['is_currency_plus_exists'] = false;
        if ($this->model_extension_price->isCurrencyPlusExists()) {
            $data['is_currency_plus_exists'] = true;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = $this->model_extension_price->changeProductExtraCharge($this->request->post);

            if ($res) {
                $this->setLog($this->request->post, $this->local_name);
                $this->session->data['success'] = $this->language->get('text_success_' . $this->local_name);

                if (isset($this->request->get['exit'])) {
                    $this->response->redirect($this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
                }
            }
            else {
                $this->error['warning'] = $this->language->get('error_license');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['user_token'] =  $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard ', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('local_title'),
            'href'      => $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->local_name),
            'href'      => $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true)
        );


        $data['action'] = $this->url->link($this->type . '/' . $this->name . '/' . $this->local_name, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link($this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true);

        $data['button_save'] = $this->language->get('button_save');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }


        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }
        else {
            $data['error'] = '';
        }

        $this->load->model('setting/extension');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['category'] = $this->load->controller('extension/price/category');
        $data['rnd'] = $this->load->controller('extension/price/rnd');

        $this->response->setOutput($this->load->view($this->pretype . $this->type . '/' . $this->local_name . $this->tpl, $data));
    }


    private function setModels() {
        $this->load->model($this->type . '/' . $this->name);

        $this->load->model('localisation/currency');
        $data['currencies'] = $this->model_localisation_currency->getCurrencies();

        $new_currencies = array();
        $new_currencies[$this->config->get('config_currency')] = $data['currencies'][$this->config->get('config_currency')];


        foreach ($data['currencies'] as $key => $val) {
            if ($key != $this->config->get('config_currency')) {
                $new_currencies[$key] = $data['currencies'][$key];
            }
        }

        $data['currencies'] = $new_currencies;

        if (file_exists(DIR_APPLICATION.'/model/sale/customer_group.php') ) {
            $this->load->model('sale/customer_group');
            $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
        }
        elseif (file_exists(DIR_APPLICATION.'/model/customer/customer_group.php') ) {
            $this->load->model('customer/customer_group');
            $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
        }


        $data['show_base_price'] = false;

        if ($this->model_extension_price->isCurrencyPlusExists() == true) {
            $data['show_base_price'] = true;
        }

        $data['show_extra_charge'] = false;

        if ($this->model_extension_price->isExtraChargeExists() == true) {
            $data['show_extra_charge'] = true;
        }

        return $data;
    }


    public function category() {
        $this->setModels();

        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        $data['show_product_groups'] = false;

        if ($this->model_extension_price->isProductGroupsExists() == true) {
            $data['show_product_groups'] = true;

            $this->load->model('catalog/product_groups');
            $results = $this->model_catalog_product_groups->getGroups();

            foreach ($results as $key => $result) {
                $data['groups'][$key] = array(
                    'group_id'	    => $result['group_id'],
                    'name'  		=> $result['caption'],
                    'status'	    => $result['status']
                );
            }
        }

        $this->load->model('catalog/category');

        $results = $this->model_catalog_category->getCategoryByParentIDCP(0);

        $data['categories'] = array();
        foreach ($results as $result) {
            $data['categories'][] = array(
                'category_id' => $result['category_id'],
                'name'        => $result['name']
            );
        }

        $this->load->model('catalog/manufacturer');

        $data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

        return $this->load->view($this->pretype . $this->type . '/' . $this->name . '_common_category' . $this->tpl, $data);
    }


    public function rnd() {
        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        return $this->load->view($this->pretype . $this->type . '/' . $this->name . '_common_rnd' . $this->tpl, $data);
    }


    public function clear() {
        $data = $this->load->language($this->pretype . $this->type . '/' . $this->name);

        if ($this->validateForm()) {
            if (file_exists(DIR_LOGS . $this->log_file_name)) {
                @unlink(DIR_LOGS . $this->log_file_name);
            }

            $this->session->data['success'] = $this->language->get('text_success_clear_log');

            $this->response->redirect($this->url->link($this->pretype . $this->type . '/' . $this->name, 'user_token=' . $this->session->data['user_token'], true));
        }
    }


    protected function setLog($arr, $type) {
        $reply = date("Y-m-d H:i:s") . " " . $type . ":    ";
        
        foreach ($arr as $key => $val) {
            $reply .= $key." => ";
            if (is_array($val)) {
                $reply .= implode(";", $val);
            }
            else {
                $reply .= $val;
            }
            $reply .= "|";
        }

        $reply .= "\n";


        if ($fp = @fopen(DIR_LOGS . $this->log_file_name, "a+")) {
            @fputs($fp, $reply);
            @fflush($fp);
            @fclose($fp);
        }
    }


    protected function validateForm() {
        if (!$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }


    protected function validateLicense() {
        $this->setModels();

        if (!$this->model_extension_price->setKey()) {
            $this->error['warning'] = $this->language->get('error_license2');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}
?>