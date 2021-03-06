<?
//namespace Runtastic;
	class Runtastic extends IPSModule
	{
		public function __construct($InstanceID)
		{
			//Never delete this line!
			parent::__construct($InstanceID);
			
			//You can add custom code below.

		}
		
		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			$this->RegisterPropertyString("usr", "");
			$this->RegisterPropertyString("pwd", "");
			
		}		
	
		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();
			
			// Eigene Profile
	        	$this->RegisterProfileInteger("RUNT.bpm", "", "", " bpm", NULL, NULL, NULL, NULL);
			$this->RegisterProfileInteger("RUNT.kcal", "", "", " kcal", NULL, NULL, NULL, NULL);
			$this->RegisterProfileInteger("RUNT.ms", "", "", " ms", NULL, NULL, NULL, NULL);
			$this->RegisterProfileInteger("RUNT.min", "", "", " min", NULL, NULL, NULL, NULL);
			$this->RegisterProfileFloat("RUNT.pace", "", "", " min/km", NULL, NULL, NULL, 2);
			$this->RegisterProfileFloat("RUNT.m", "", "", " m", NULL, NULL, NULL, NULL);
			$this->RegisterProfileFloat("RUNT.km", "", "", " km", NULL, NULL, NULL, 2);
			
			
			// Integer
			$this->RegisterVariableFloat("id", "Aktivitaets-ID");
			$this->RegisterVariableInteger("type_id", "Aktivitaets-Typ-ID");
			$this->RegisterVariableInteger("kcal", "kcal", "RUNT.kcal");
			$this->RegisterVariableInteger("heartrate_avg", "Durchschnittspuls", "RUNT.bpm");
			$this->RegisterVariableInteger("heartrate_max", "Maximalpuls", "RUNT.bpm");
			$this->RegisterVariableInteger("weather_id", "Wetter-ID");
			$this->RegisterVariableInteger("feeling_id", "Feeling-ID");
			$this->RegisterVariableInteger("surface_id", "Oberflaechenbeschaffenheits-ID");
			$this->RegisterVariableInteger("year", "Jahr");
			$this->RegisterVariableInteger("month", "Monat"); // kommt als String
			$this->RegisterVariableInteger("day", "Tag"); // kommt als String
			$this->RegisterVariableInteger("hour", "Stunde");
			$this->RegisterVariableInteger("minutes", "Minute");
			$this->RegisterVariableInteger("seconds", "Sekunde");
			$this->RegisterVariableInteger("duration_ms", "Dauer ms", "RUNT.ms");
			$this->RegisterVariableInteger("duration_min", "Dauer min", "RUNT.min");

			
			// Strings
			$this->RegisterVariableString("type", "Aktivitaets-Typ");
			$this->RegisterVariableString("surface", "Oberflaechenbeschaffenheit");
			$this->RegisterVariableString("weather", "Wetter");
			$this->RegisterVariableString("feeling", "Feeling");
			$this->RegisterVariableString("notes", "Notizen");
			$this->RegisterVariableString("page_url", "page_url");
			$this->RegisterVariableString("create_route_url", "create_route_url");
			$this->RegisterVariableString("map_url", "map_url");
			
			// Floats
			$this->RegisterVariableFloat("distance", "Strecke", "RUNT.km"); // kommt als Int
			$this->RegisterVariableFloat("pace", "Aktivitaets-Pace", "RUNT.pace");
			$this->RegisterVariableFloat("speed", "Geschwindigkeit", "WindSpeed.kmh"); // kommt als String
			$this->RegisterVariableFloat("elevation_gain", "Maximaler Höhenunterschied", "RUNT.m");
			$this->RegisterVariableFloat("elevation_loss", "Höhenunterschied", "RUNT.m");
			
			// Bool
			$this->RegisterVariableBoolean("ndr", "Neue Daten empfangen");
			
			
			// Eigene Scripte
			$sid = $this->RegisterScript("RuntasticdatenAbrufen", "Runtasticdaten abrufen", 
			'<?
				RUNT_RequestInfo(' . $this->InstanceID . ');
			?>'
			, -8);

   
		}
	
		/**
		* This function will be available automatically after the module is imported with the module control.
		* Using the custom prefix this function will be callable from PHP and JSON-RPC through:
		*
		* UWZ_RequestInfo($id);
		*
		*/
				
		
		public function RequestInfo()
		{
			$this->loginUsername = $this->ReadPropertyString("usr");
			$this->loginPassword = $this->ReadPropertyString("pwd"); 
		
			$runtastic = new CL_Runtastic();

			$runtastic->setUsername($this->loginUsername)->setPassword($this->loginPassword);
			$runtastic->login();
			
			$activities = $runtastic->getActivities();
		echo "HALLO".$activities;	
			// neue Daten abrufen, nur eintragen, wenn neue ID
		//	if (GetValue($this->GetIDForIdent("id")) <> $activities[0]->id)
		//	{
		//		SetValue($this->GetIDForIdent("ndr"), true);
			
			// ID & so
			SetValue($this->GetIDForIdent("id"), $activities[0]->id);
			SetValue($this->GetIDForIdent("type"), $activities[0]->type);
			SetValue($this->GetIDForIdent("type_id"), $activities[0]->type_id);
			SetValue($this->GetIDForIdent("duration_min"), ($activities[0]->duration)/60000);
			SetValue($this->GetIDForIdent("duration_ms"), $activities[0]->duration);
			SetValue($this->GetIDForIdent("distance"), ((float)$activities[0]->distance)/1000.0);
			SetValue($this->GetIDForIdent("pace"), $activities[0]->pace);
			SetValue($this->GetIDForIdent("speed"), (float)$activities[0]->speed);
			SetValue($this->GetIDForIdent("kcal"), $activities[0]->kcal);
			SetValue($this->GetIDForIdent("heartrate_avg"), $activities[0]->heartrate_avg);
			SetValue($this->GetIDForIdent("heartrate_max"), $activities[0]->heartrate_max);
			SetValue($this->GetIDForIdent("elevation_gain"), $activities[0]->elevation_gain);
			SetValue($this->GetIDForIdent("elevation_loss"), $activities[0]->elevation_loss);
			SetValue($this->GetIDForIdent("surface"), $activities[0]->surface);
			SetValue($this->GetIDForIdent("weather"), $activities[0]->weather);
			SetValue($this->GetIDForIdent("feeling"), $activities[0]->feeling);
			SetValue($this->GetIDForIdent("weather_id"), $activities[0]->weather_id);
			SetValue($this->GetIDForIdent("feeling_id"), $activities[0]->feeling_id);
			SetValue($this->GetIDForIdent("surface_id"), $activities[0]->surface_id);
			SetValue($this->GetIDForIdent("notes"), $activities[0]->notes);
			SetValue($this->GetIDForIdent("page_url"), "http://www.runtastic.com" . $activities[0]->page_url);
			SetValue($this->GetIDForIdent("map_url"), $activities[0]->map_url);
			SetValue($this->GetIDForIdent("create_route_url"), "http://www.runtastic.com" . $activities[0]->create_route_url);
			
			// Datum und Uhrzeit
			SetValue($this->GetIDForIdent("year"), $activities[0]->date->year);
			SetValue($this->GetIDForIdent("month"), (int)$activities[0]->date->month);
			SetValue($this->GetIDForIdent("day"), (int)$activities[0]->date->day);
			SetValue($this->GetIDForIdent("hour"), $activities[0]->date->hour);
			SetValue($this->GetIDForIdent("minutes"), $activities[0]->date->minutes);
			SetValue($this->GetIDForIdent("seconds"), $activities[0]->date->seconds);
			
		//	}

		}

	    protected function RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits)
		{
			if (!IPS_VariableProfileExists($Name))
			{
				IPS_CreateVariableProfile($Name, 1);
			}
			else
			{
				$profile = IPS_GetVariableProfile($Name);
				if ($profile['ProfileType'] != 1)
					throw new Exception("Variable profile type does not match for profile " . $Name);
			}
			IPS_SetVariableProfileIcon($Name, $Icon);
			IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
			IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
			IPS_SetVariableProfileDigits($Name, $Digits);
		}

	    protected function RegisterProfileFloat($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits)
		{
			if (!IPS_VariableProfileExists($Name))
			{
				IPS_CreateVariableProfile($Name, 2);
			}
			else
			{
				$profile = IPS_GetVariableProfile($Name);
				if ($profile['ProfileType'] != 2)
					throw new Exception("Variable profile type does not match for profile " . $Name);
			}
			IPS_SetVariableProfileIcon($Name, $Icon);
			IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
			IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
			IPS_SetVariableProfileDigits($Name, $Digits);
		}

	}
	
	
	class CL_Runtastic
	{
	    /**
		 * HTTP Responses
		 */
		const HTTP_OK   = 200;

		/**
		 * Runtastic API Urls
		 */
		const RUNTASTIC_LOGIN_URL           = "https://www.runtastic.com/en/d/users/sign_in.json";
		const RUNTASTIC_LOGOUT_URL          = "https://www.runtastic.com/en/d/users/sign_out";
		const RUNTASTIC_SESSIONS_URL        = "https://www.runtastic.com/api/run_sessions/json";
		const RUNTASTIC_SPORT_SESSIONS_URL  = "https://www.runtastic.com/en/users/%s/sport-sessions";

		/**
		 * Runtastic Credentials
		 */
		private $loginUsername;
		private $loginPassword;

		/**
		 * Request Trace
		 */
		private $lastRequest;
		private $lastRequestData;
		private $lastRequestInfo;

		/**
		 * Runtastic User Data after login
		 */
		private $username;
		private $uid;
		private $token;
		private $rawData;

		/**
		 * Other private variables
		 */
		private $doc;
		private $loggedIn  = false;
		private $timeout   = 10;
		private $cookieJar = "cookiejar";// /usr/lib/symcon/modules/ips-runtastic/runtastic/cookiejar";

		/**
		 * Runtastic constructor.
		 */
		public function __construct()
		{
			libxml_use_internal_errors(true);
			$this->doc = new \DOMDocument();
		}
	
		
		
		/**
		 * Set Login Username.
		 *
		 * @param  string $loginUsername
		 * @return Runtastic
		 */

		 
		public function setUsername($loginUsername)
		{
			$this->loginUsername = $loginUsername;

			return $this;
		}

		/**
		 * Set Login Password.
		 *
		 * @param  string $loginPassword
		 * @return Runtastic
		 */


	    public function setPassword($loginPassword)
		{
			$this->loginPassword = $loginPassword;

			return $this;
		}

		/**
		 * Set Timeout.
		 *
		 * @param  int $timeout
		 * @return Runtastic
		 */
		public function setTimeout($timeout)
		{
			$this->timeout = $timeout;

			return $this;
		}

		/**
		 * Set CookieJar File
		 *
		 * @param  string $cookieJar
		 * @return Runtastic
		 */
		public function setCookieJar($cookieJar)
		{
			$this->cookieJar = $cookieJar;

			return $this;
		}

		/**
		 * Get Username.
		 *
		 * @return string
		 */
		protected function getUsername()
		{
			return $this->username;
		}

		/**
		 * Get Uid.
		 *
		 * @return string
		 */
		protected function getUid()
		{
			return $this->uid;
		}

		/**
		 * Get Token.
		 *
		 * @return string
		 */
		protected function getToken()
		{
			return $this->token;
		}

		/**
		 * Get Raw Data.
		 *
		 * @return array
		 */
		protected function getRawData()
		{
			return $this->rawData;
		}

		/**
		 * Get Response Status Code.
		 *
		 * @return int|null
		 */
		protected function getResponseStatusCode()
		{
			if (isset($this->lastRequestInfo['http_code'])) {
				return $this->lastRequestInfo['http_code'];
			}

			return null;
		}

		/**
		 * Set Data From Response.
		 *
		 * This function parse the given request and save some variables
		 * (such as token, username, ...) into the class for future needs.
		 *
		 * @param  string $response
		 * @return void
		 */
		protected function setDataFromResponse($response)
		{
			$this->doc->loadHTML($response);

			$inputTags = $this->doc->getElementsByTagName('input');
			foreach ($inputTags as $inputTag) {
				if ($inputTag->getAttribute("name") == "authenticity_token") {
					$this->token = $inputTag->getAttribute("value");
					break;
				}
			}

			$aTags = $this->doc->getElementsByTagName('a');
			foreach ($aTags as $aTag) {
				if (preg_match("/\/en\/users\/(.*)\/dashboard/", $aTag->getAttribute("href"), $matches)) {
					$this->username = $matches[1];
					break;
				}
			}

			$scriptTags = $this->doc->getElementsByTagName('script');
			foreach ($scriptTags as $scriptTag) {
				if (strstr($scriptTag->nodeValue, 'index_data')) {
					$this->rawData = $scriptTag->nodeValue;
					break;
				}
			}

			preg_match("/uid: (.*)\,/", $this->rawData, $matches);
			if (isset($matches[1])) {
				$this->uid = $matches[1];
			}
		}

		/**
		 * Login User to Runtastic
		 *
		 * @return bool
		 */
		public function login()
		{
			$this->loggedIn = false;

			$postData = [
				"user[email]"           => $this->loginUsername,
				"user[password]"        => $this->loginPassword,
				"authenticity_token"    => $this->token,
			];

			$responseOutputJson = $this->post(self::RUNTASTIC_LOGIN_URL, $postData);
var_dump($responseOutputJson);			
if ($this->getResponseStatusCode() == self::HTTP_OK) {
				$this->setDataFromResponse($responseOutputJson->update);

				$frontpageOutput = $this->get(sprintf(self::RUNTASTIC_SPORT_SESSIONS_URL, $this->getUsername()), [], false);
				$this->setDataFromResponse($frontpageOutput);

				$this->loggedIn = true;
			}

			return $this->loggedIn;
		}

		/**
		 * Logout User's Session
		 *
		 * @return void
		 */
		protected function logout()
		{
			$this->get(self::RUNTASTIC_LOGOUT_URL);

			if ($this->getResponseStatusCode() == self::HTTP_OK) {
				$this->loggedIn = false;
			}
		}

		/**
		 * Returns all activities.
		 *
		 * If
		 *  - $iWeek is set, only the requested week will be returned.
		 *  - $iMonth is set, only the requested month will be returned.
		 *  - $iYear is set, only the requested year will be returned.
		 *
		 * $iWeek and $iMonth can be used together with $iYear. if $iYear is null, the current year will
		 * be used for filtering.
		 *
		 * @param  int|null $iWeek  Number of the wanted week.
		 * @param  int|null $iMonth Number of the requested month.
		 * @param  int|null $iYear  Number of the requested year.
		 * @return bool|mixed
		 */
		public function getActivities($iWeek = null, $iMonth = null, $iYear = null)
		{
			$response = [];

			if (!$this->loggedIn) {
				$this->login();
			}

			if ($this->loggedIn) {
				preg_match("/var index_data = (.*)\;/", $this->rawData, $matches);
				$itemJsonData = json_decode($matches[1]);
				$items = [];

				// Complete $iMonth with leading zeros
				if (!is_null($iMonth)) {
					$iMonth = str_pad($iMonth, 2, '0', STR_PAD_LEFT);
				}

				if (is_null($iYear)) {
					$iYear = date("Y");
				}

				foreach ($itemJsonData as $item) {
					if (!is_null($iWeek)) { /* Get week statistics */
						$sMonday = date("Y-m-d", strtotime("{$iYear}-W{$iWeek}"));
						$sSunday = date("Y-m-d", strtotime("{$iYear}-W{$iWeek}-7"));
						if ($sMonday <= $item[1] && $sSunday >= $item[1]) {
							$items[] = $item[0];
						}
					} elseif (!is_null($iMonth)) { /* Get month statistics */
						$tmpDate = $iYear."-".$iMonth."-";
						if ($tmpDate."01" <= $item[1] && $tmpDate."31" >= $item[1]) {
							$items[] = $item[0];
						}
					} elseif (!is_null($iYear)) { /* Get year statistics */
						$tmpDate = $iYear."-";
						if ($tmpDate."01-01" <= $item[1] && $tmpDate."12-31" >= $item[1]) {
							$items[] = $item[0];
						}
					} else { /* Get all statistics */
						$items[] = $item[0];
					}
				}

				// Sort activities by ID (which is the same that sorting by date)
				arsort($items);

				$postData = [
					"user_id"            => $this->getUid(),
					"items"              => join(',', $items),
					"authenticity_token" => $this->getToken(),
				];

				$response = $this->post(self::RUNTASTIC_SESSIONS_URL, $postData);
var_sump($response);
			}

			//return new RuntasticActivityList($response);
			return $response;
		}

		/**
		 * Appends query array onto URL
		 *
		 * @param  string $url
		 * @param  array  $query
		 * @return string
		 */
		protected function parseGet($url, $query)
		{
			if (!empty($query)) {
				$append = strpos($url, '?') === false ? '?' : '&';

				return $url.$append.http_build_query($query);
			}

			return $url;
		}

		/**
		 * Parses JSON as PHP object
		 *
		 * @param  string $response
		 * @return object
		 */
		protected function parseResponse($response)
		{
			return @json_decode($response, false, 512, JSON_BIGINT_AS_STRING);
		}

		/**
		 * Makes HTTP Request to the API
		 *
		 * @param  string      $url
		 * @param  array       $parameters
		 * @param  string|null $request
		 * @param  bool        $json
		 * @return object|null
		 */
		protected function request($url, $parameters = [], $request = null, $json = true)
		{
			$this->lastRequest     = $url;
			$this->lastRequestData = $parameters;

			$curl = curl_init($url);

			$curlOptions = array(
				CURLOPT_URL             => $url,
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_COOKIEFILE      => $this->cookieJar,
				CURLOPT_COOKIEJAR       => $this->cookieJar,
				CURLOPT_TIMEOUT         => $this->timeout,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_SSL_VERIFYHOST  => 0,
			);

			if (!empty($parameters) || !empty($request)) {
				if (!empty($request)) {
					$curlOptions[CURLOPT_CUSTOMREQUEST] = $request;
					$parameters = http_build_query($parameters);
				} else {
					$curlOptions[CURLOPT_POST] = true;
				}

				$curlOptions[CURLOPT_POSTFIELDS] = $parameters;
			}

			curl_setopt_array($curl, $curlOptions);
			$response = curl_exec($curl);
			echo "HALLO".$curl;
			$this->lastRequestInfo = curl_getinfo($curl);
			curl_close($curl);

			return !$response ? null : ($json ? $this->parseResponse($response) : $response);
		}

		/**
		 * Sends GET request to specified API endpoint
		 *
		 * @param  string $request
		 * @param  array  $parameters
		 * @param  bool   $json
		 * @return string
		 */
		protected function get($request, $parameters = [], $json = true)
		{
			$requestUrl = $this->parseGet($request, $parameters);

			return $this->request($requestUrl, [], null, $json);
		}

		/**
		 * Sends PUT request to specified API endpoint
		 *
		 * @param  string $request
		 * @param  array  $parameters
		 * @param  bool   $json
		 * @return string
		 */
		protected function put($request, $parameters = [], $json = true)
		{
			return $this->request($request, $parameters, 'PUT', $json);
		}

		/**
		 * Sends POST request to specified API endpoint
		 *
		 * @param  string $request
		 * @param  array  $parameters
		 * @param  bool   $json
		 * @return string
		 */
		protected function post($request, $parameters = [], $json = true)
		{
			return $this->request($request, $parameters, null, $json);
		}

		/**
		 * Sends DELETE request to specified API endpoint
		 *
		 * @param  string $request
		 * @param  array  $parameters
		 * @param  bool   $json
		 * @return string
		 */
		protected function delete($request, $parameters = [], $json = true)
		{
			return $this->request($request, $parameters, 'DELETE', $json);
		}
	
	}

?>
