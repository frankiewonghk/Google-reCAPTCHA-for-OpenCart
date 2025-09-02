<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Captcha;
/**
 * Class Recaptcha
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Captcha
 */
class Recaptcha extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/captcha/recaptcha');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=captcha')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/captcha/recaptcha', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/captcha/recaptcha.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=captcha');

		$data['captcha_recaptcha_status'] = $this->config->get('captcha_recaptcha_status');
		$data['captcha_recaptcha_site_key'] = $this->config->get('captcha_recaptcha_site_key');
		$data['captcha_recaptcha_secret_key'] = $this->config->get('captcha_recaptcha_secret_key');
		$data['captcha_recaptcha_score_threshold'] = $this->config->get('captcha_recaptcha_score_threshold') ?: 0.5;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/captcha/recaptcha', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/captcha/recaptcha');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/captcha/recaptcha')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['captcha_recaptcha_site_key'])) {
			$json['error'] = $this->language->get('error_site_key');
		}

		if (empty($this->request->post['captcha_recaptcha_secret_key'])) {
			$json['error'] = $this->language->get('error_secret_key');
		}

		if (!is_numeric($this->request->post['captcha_recaptcha_score_threshold']) || 
			$this->request->post['captcha_recaptcha_score_threshold'] < 0 || 
			$this->request->post['captcha_recaptcha_score_threshold'] > 1) {
			$json['error'] = $this->language->get('error_score_threshold');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('captcha_recaptcha', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		// Setting
		$this->load->model('setting/setting');

		$this->model_setting_setting->editSetting('captcha_recaptcha', [
			'captcha_recaptcha_status' => 0,
			'captcha_recaptcha_site_key' => '',
			'captcha_recaptcha_secret_key' => '',
			'captcha_recaptcha_score_threshold' => 0.5
		]);
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
		// Setting
		$this->load->model('setting/setting');

		$this->model_setting_setting->deleteSetting('captcha_recaptcha');
	}
}
