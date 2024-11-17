<?php
function fetch_products(int $page = 1) : ?array
{
	$per_page = 16;
	$from = $page * $per_page;
	$to = $from + $per_page;

	$url = 'https://www.buskool.com/user/get_product_list';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'accept: application/json, text/plain, */*',
		'accept-language: en-US,en;q=0.9,fa;q=0.8,it;q=0.7',
		'cache-control: no-cache',
		'content-type: application/json;charset=UTF-8',
		'origin: https://www.buskool.com',
		'pragma: no-cache',
		'priority: u=1, i',
		'referer: https://www.buskool.com/product-list',
		'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Google Chrome";v="126"',
		'sec-ch-ua-mobile: ?0',
		'sec-ch-ua-platform: "Windows"',
		'sec-fetch-dest: empty',
		'sec-fetch-mode: cors',
		'sec-fetch-site: same-origin',
		'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
		'x-csrf-token: nKPN1lCdaBYkbgYaZIlQvw1AgVXaS9Fy03TLtTND',
		'x-requested-with: XMLHttpRequest',
	]);
	curl_setopt($ch, CURLOPT_COOKIE, '_pk_id.2.d78f=6c820aceb96c28e8.1731272328.; laravel_session=wdMwnqRFXYTJsde84vi8G0ZdsxebCWDw4Ho3la1D; _pk_ref.2.d78f=%5B%22%22%2C%22%22%2C1731825862%2C%22https%3A%2F%2Fwww.google.com%2F%22%5D; _pk_ses.2.d78f=1');
	curl_setopt($ch, CURLOPT_POSTFIELDS, '{"from_record_number":'. $from .',"response_rate":false,"to_record_number":'. $to .',"sort_by":"RD","client":"web"}');

	$response = curl_exec($ch);

	if (curl_errno($ch)) {
		return null;
	}

	curl_close($ch);

	$obj = json_decode($response, true);
	if (json_last_error() === JSON_ERROR_NONE) {
		return $obj;
	}
	return null;
}

@mkdir("data/");

$page = 1;
while (true) {
	$obj = fetch_products($page);

    file_put_contents("data/$page.json", json_encode($obj, JSON_UNESCAPED_UNICODE));

	if (!isset($obj["products"]) || !is_array($obj["products"]) || count($obj["products"]) === 0) {
		break;
	}

	$page++;
}
