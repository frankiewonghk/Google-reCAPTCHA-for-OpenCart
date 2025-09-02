<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Captcha;
/**
 * Class Recaptcha
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Captcha
 */
class Recaptcha extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('extension/opencart/captcha/recaptcha');

		$data['route'] = (string)$this->request->get['route'];
		$data['site_key'] = $this->config->get('captcha_recaptcha_site_key');

		return $this->load->view('extension/opencart/captcha/recaptcha', $data);
	}

	/**
	 * Validate
	 *
	 * @return string
	 */
	public function validate(): string {
		$this->load->language('extension/opencart/captcha/recaptcha');

		if (!isset($this->request->post['g-recaptcha-response'])) {
			return $this->language->get('error_captcha');
		}

		$recaptcha_secret = $this->config->get('captcha_recaptcha_secret_key');
		
		if (empty($recaptcha_secret)) {
			return $this->language->get('error_configuration');
		}

		$recaptcha_response = $this->request->post['g-recaptcha-response'];
		$remote_ip = $this->request->server['REMOTE_ADDR'];

		// Verify with Google reCAPTCHA API
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = [
			'secret' => $recaptcha_secret,
			'response' => $recaptcha_response,
			'remoteip' => $remote_ip
		];

		$options = [
			'http' => [
				'header' => "Content-type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query($data)
			]
		];

		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result, true);

		if (!$response['success']) {
			return $this->language->get('error_captcha');
		}

		// Check score threshold (0.0 is very likely a bot, 1.0 is very likely a human)
		$score_threshold = $this->config->get('captcha_recaptcha_score_threshold') ?: 0.5;
		
		if ($response['score'] < $score_threshold) {
			return $this->language->get('error_score');
		}

		return '';
	}
}
