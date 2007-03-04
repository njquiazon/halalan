<?php

require_once APP_CORE . "/HypDao.class.php";

class Vote extends HypDao {

	function insert($entity) {
		$db = parent::connect();
		$GLOBALS['ADODB_FORCE_TYPE'] = 0;
		return $db->autoExecute('votes', $entity, 'INSERT');
	}

	function selectAll() {
		$db = parent::connect();
		return $db->getAll("SELECT * FROM votes");
	}

	function selectAllByVoterIDAndPositionID($voterid, $positionid) {
		$db = parent::connect();
		return $db->getAll("SELECT * FROM votes JOIN candidates USING (candidateid) JOIN positions USING (positionid) WHERE voterid = ? AND positionid = ?", array($voterid, $positionid));
	}

	function countAllByPositionID($positionid) {
		$db = parent::connect();
		return $db->getAll("SELECT count(votes.candidateid) AS votes, candidates.candidateid FROM votes RIGHT JOIN candidates USING (candidateid) JOIN positions USING (positionid) WHERE positionid = ? GROUP BY candidateid ORDER BY votes DESC", array($positionid));
	}

}

?>