<?php

class plxMyAutoMetaDescription extends plxPlugin {

	/**
	 * Constructeur de la classe
	 *
	 * @param	default_lang	langue par d�faut utilis�e par PluXml
	 * @return	null
	 * @author	Stephane F
	 **/
	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);

		# droits pour acc�der � la page config.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);

		# D�clarations des hooks
		$this->addHook('plxShowMeta', 'plxShowMeta');

	}

	/**
	 * M�thode qui extrait n mots dans une chaine de caract�res
	 *
	 * @param 	$string 	chaine � couper
	 * @param 	$len 		nombre de mots � garder
	 * @param 	$ending 	caract�res de fin
	 * @param 	$char 		caract�re de s�paration
	 * @return	string
	 * @author	Stephane F
	 **/
	public static function subtok($string,$len=25,$ending='...',$chr=' ') {
		$explode=explode($chr,$string);
		if(sizeof($explode)>$len)
			return implode($chr,array_slice($explode,0,$len)).$ending;
		else
  			return implode($chr,array_slice($explode,0,$len));
	}

	/**
	 * M�thode renseigne le meta description de l'article
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function plxShowMeta() {

		echo '<?php
			if($this->plxMotor->mode=="article" AND strtolower($meta)=="description") {

				$description=trim($this->plxMotor->plxRecord_arts->f("meta_description"));
				if(!empty($description)) {
					echo "<meta name=\"description\" content=\"".$description."\" />\n";
					return true;
				}

				$chapo=trim($this->plxMotor->plxRecord_arts->f("chapo"));
				$content=trim($this->plxMotor->plxRecord_arts->f("content"));
				$description=strip_tags($chapo." ".$content); # suppression des balises html
				if(!empty($description)) {
					$description = str_replace("\"","\'",$description); # pour prot�ger le champ content de la balise meta
					$description = plxMyAutoMetaDescription::subtok($description,'.$this->getParam('nbwords').'); # on coupe
					echo "<meta name=\"description\" content=\"".$description."\" />\n";
					return true;
				}

				$description=trim($this->plxMotor->aConf["meta_description"]);
				if(!empty($description)) {
					echo "<meta name=\"description\" content=\"".$description."\" />\n";
					return true;
				}
			}
		?>';

	}

}
?>