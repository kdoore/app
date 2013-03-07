<?php

class WAMServiceTest extends WikiaBaseTest {

	protected function getReflectionMethod ($name) {
		$class = new ReflectionClass('WAMService');
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

	/**
	 * @dataProvider getWamIndexConditionsDataProvider
	 */
	public function testGetWamIndexConditions ($options, $expConds) {
		$dbMock = $this->getMock('stdClass', array('strencode'));

		$dbMock->expects($this->any())
			->method('strencode')
			->will($this->returnArgument(0));

		$getWamIndexConditions = $this->getReflectionMethod('getWamIndexConditions');
		$dataMartService = new WAMService();
		$actConds = $getWamIndexConditions->invoke($dataMartService, $options, $dbMock);
		$this->assertEquals($expConds, $actConds);
	}

	public function getWamIndexConditionsDataProvider () {
		return array(
			array(
				array(
					'currentTimestamp' => 100000,
					'previousTimestamp' => 80000,
					'wikiId' => 0,
					'wikiWord' => null,
					'verticalId' => 0,
					'wikiLang' => null
				),
				array(
					'fw1.time_id = FROM_UNIXTIME(100000)',
					'fw2.time_id = FROM_UNIXTIME(80000)',
					'dw.hub_id' => array(2, 3, 9)
				)
			),
			array(
				array(
					'currentTimestamp' => 1000000,
					'previousTimestamp' => 80000,
					'wikiId' => 0,
					'wikiWord' => null,
					'verticalId' => 0,
					'wikiLang' => null
				),
				array(
					'fw1.time_id = FROM_UNIXTIME(1000000)',
					'fw2.time_id = FROM_UNIXTIME(80000)',
					'dw.hub_id' => array(2, 3, 9)
				)
			),
			array(
				array(
					'currentTimestamp' => 1000000,
					'previousTimestamp' => 80000,
					'wikiId' => 2233,
					'wikiWord' => null,
					'verticalId' => 0,
					'wikiLang' => null
				),
				array(
					'fw1.time_id = FROM_UNIXTIME(1000000)',
					'fw2.time_id = FROM_UNIXTIME(80000)',
					'fw1.wiki_id' => 2233,
					'dw.hub_id' => array(2, 3, 9)
				)
			),
			array(
				array(
					'currentTimestamp' => 1000000,
					'previousTimestamp' => 80000,
					'wikiId' => 2233,
					'verticalId' => 1,
					'wikiWord' => null,
					'wikiLang' => null
				),
				array(
					'fw1.time_id = FROM_UNIXTIME(1000000)',
					'fw2.time_id = FROM_UNIXTIME(80000)',
					'fw1.wiki_id' => 2233,
					'dw.hub_id' => 1
				)
			),
			array(
				array(
					'currentTimestamp' => 1000000,
					'previousTimestamp' => 80000,
					'wikiId' => 0,
					'verticalId' => 0,
					'wikiWord' => 'testWord',
					'wikiLang' => null
				),
				array(
					'fw1.time_id = FROM_UNIXTIME(1000000)',
					'fw2.time_id = FROM_UNIXTIME(80000)',
					"dw.url like '%testWord%' OR dw.title like '%testWord%'",
					'dw.hub_id' => array(2, 3, 9),
				)
			),
			array(
				array(
					'currentTimestamp' => 100000,
					'previousTimestamp' => 80000,
					'wikiId' => 0,
					'wikiWord' => null,
					'verticalId' => 0,
					'wikiLang' => 'testLang'
				),
				array(
					'fw1.time_id = FROM_UNIXTIME(100000)',
					'fw2.time_id = FROM_UNIXTIME(80000)',
					'dw.hub_id' => array(2, 3, 9),
					'dw.lang' => 'testLang'
				)
			),
			array(
				array(
					'currentTimestamp' => 100000,
					'previousTimestamp' => 80000,
					'wikiId' => 666,
					'wikiWord' => 'testWord2',
					'verticalId' => 5,
					'wikiLang' => 'testLang'
				),
				array(
					'fw1.time_id = FROM_UNIXTIME(100000)',
					'fw2.time_id = FROM_UNIXTIME(80000)',
					'fw1.wiki_id' => 666,
					"dw.url like '%testWord2%' OR dw.title like '%testWord2%'",
					'dw.hub_id' => 5,
					'dw.lang' => 'testLang'
				)
			),
		);
	}

	/**
	 * @dataProvider getWamIndexOptionsDataProvider
	 */
	public function testGetWamIndexOptions($options, $expConds) {
		$getWamIndexOptions = $this->getReflectionMethod('getWamIndexOptions');
		$dataMartService = new WAMService();
		$actConds = $getWamIndexOptions->invoke($dataMartService, $options);
		$this->assertEquals($expConds, $actConds);
	}

	public function getWamIndexOptionsDataProvider () {
		return array(
			array(
				array(
					'sortDirection' => null,
					'sortColumn' => null,
					'offset' => 0,
					'limit' => 20
				),
				array(
					'ORDER BY' => 'wam ASC',
					'OFFSET' => 0,
					'LIMIT' => 20
				),
			),
			array(
				array(
					'sortDirection' => null,
					'sortColumn' => null,
					'offset' => 4,
					'limit' => 15
				),
				array(
					'ORDER BY' => 'wam ASC',
					'OFFSET' => 4,
					'LIMIT' => 15
				),
			),
			array(
				array(
					'sortDirection' => 'ASC',
					'sortColumn' => null,
					'offset' => 0,
					'limit' => 20
				),
				array(
					'ORDER BY' => 'wam ASC',
					'OFFSET' => 0,
					'LIMIT' => 20
				),
			),
			array(
				array(
					'sortDirection' => 'DESC',
					'sortColumn' => null,
					'offset' => 0,
					'limit' => 20
				),
				array(
					'ORDER BY' => 'wam DESC',
					'OFFSET' => 0,
					'LIMIT' => 20
				),
			),
			array(
				array(
					'sortDirection' => null,
					'sortColumn' => 'wam_rank',
					'offset' => 0,
					'limit' => 20
				),
				array(
					'ORDER BY' => 'wam ASC',
					'OFFSET' => 0,
					'LIMIT' => 20
				),
			),
			array(
				array(
					'sortDirection' => null,
					'sortColumn' => 'wam_change',
					'offset' => 0,
					'limit' => 20
				),
				array(
					'ORDER BY' => 'wam_change ASC',
					'OFFSET' => 0,
					'LIMIT' => 20
				),
			),
			array(
				array(
					'sortDirection' => 'DESC',
					'sortColumn' => 'wam_change',
					'offset' => 1,
					'limit' => 5
				),
				array(
					'ORDER BY' => 'wam_change DESC',
					'OFFSET' => 1,
					'LIMIT' => 5
				),
			),
		);
	}
}
