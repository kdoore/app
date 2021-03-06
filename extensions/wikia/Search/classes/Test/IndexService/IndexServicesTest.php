<?php
/**
 * Class definition for \Wikia\Search\Test\IndexService\IndexServicesTest
 * @author relwell
 */
namespace Wikia\Search\Test\IndexService;
use Wikia\Search\IndexService, Wikia\Search\MediaWikiService, \ReflectionProperty, \ReflectionMethod, Wikia\Search\Test\BaseTest;
/**
 * Tests the methods found in concrete classes within the \Wikia\Search\IndexService namespace
 * @author relwell
 */
class IndexServicesTest extends BaseTest
{
	public function setUp() {
		parent::setUp();
		$this->service = $this->getMockBuilder( '\Wikia\Search\MediaWikiService' )
		                        ->disableOriginalConstructor();
		$this->pageId = 123;
	}
	
	protected function injectService( $service, $mwservice ) {
		$refl = new ReflectionProperty( '\Wikia\Search\IndexService\AbstractService', 'service' );
		$refl->setAccessible( true );
		$refl->setValue( $service, $mwservice );
	}
	
	/**
	 * @covers Wikia\Search\IndexService\BacklinkCount::execute
	 */
	public function testBacklinkCountExecute() {
		$mwservice = $this->service->setMethods( array( 'getBacklinksCountFromPageId' ) )->getMock();
		$mwservice
		    ->expects( $this->at( 0 ) )
		    ->method ( 'getBacklinksCountFromPageId' )
		    ->with   ( $this->pageId )
		    ->will   ( $this->returnValue( 20 ) )
		;
		$service = $this->getMockBuilder( '\Wikia\Search\IndexService\BacklinkCount' )
		                ->disableOriginalConstructor()
		                ->setMethods( null )
		                ->getMock();
		
		$service->setPageId( $this->pageId );
		
		$this->injectService( $service, $mwservice );
		
		$this->assertEquals(
				array( 'backlinks' => 20 ),
				$service->execute()
		);
	}
	
	/**
	 * @covers Wikia\Search\IndexService\Metadata::execute
	 */
	public function testMetadataExecuteInternal() {
		$service = $this->getMockBuilder( '\Wikia\Search\IndexService\Metadata' )
		                ->disableOriginalConstructor()
		                ->setMethods( null )
		                ->getMock();
		$mwservice = $this->service->setMethods( array( 'getGlobal' ) )->getMock();
		$mwservice
		    ->expects( $this->at( 0 ) )
		    ->method ( 'getGlobal' )
		    ->with   ( 'ExternalSharedDB' )
		    ->will   ( $this->returnValue( false ) )
		;
		$this->injectService( $service, $mwservice );
		$this->assertEmpty(
				$service->execute()
		);
	}
	
	/**
	 * @covers Wikia\Search\IndexService\Metadata::execute
	 */
    public function testMetadataExecuteInternalNoPageId() {
		$service = $this->getMockBuilder( '\Wikia\Search\IndexService\Metadata' )
		                ->disableOriginalConstructor()
		                ->setMethods( null )
		                ->getMock();
		$mwservice = $this->service->setMethods( array( 'getGlobal' ) )->getMock();
		$mwservice
		    ->expects( $this->at( 0 ) )
		    ->method ( 'getGlobal' )
		    ->with   ( 'ExternalSharedDB' )
		    ->will   ( $this->returnValue( true ) )
		;
		$this->injectService( $service, $mwservice );
		try {
			$service->execute();
		} catch ( \Exception $e ) { }
		
		$this->assertInstanceOf(
				'\Exception',
				$e
		);
	}
	
	/**
	 * @covers Wikia\Search\IndexService\Metadata::execute
	 */
    public function testMetadataExecuteInternalSuccess() {
		$service = $this->getMockBuilder( '\Wikia\Search\IndexService\Metadata' )
		                ->disableOriginalConstructor()
		                ->setMethods( null )
		                ->getMock();
		$mwservice = $this->service->setMethods( array( 'getGlobal', 'getApiStatsForPageId' ) )->getMock();
		
		$pageData = array( 'views' => 123, 'revcount' => 234, 'created' => 'yesterday', 'touched' => 'today' );
		$apiResult = array(
				'query' => array( 'pages' => array( $this->pageId => $pageData ) ,
						          'category' => array( 'catname' => 'stuff' ) ) 
				);
		
		$mwservice
		    ->expects( $this->at( 0 ) )
		    ->method ( 'getGlobal' )
		    ->with   ( 'ExternalSharedDB' )
		    ->will   ( $this->returnValue( true ) )
		;
		$mwservice
		    ->expects( $this->at( 1 ) )
		    ->method ( 'getApiStatsForPageId' )
		    ->with   ( $this->pageId )
		    ->will   ( $this->returnValue( $apiResult ) )
		;
		$service->setPageId( $this->pageId );
		$this->injectService( $service, $mwservice );
		$expected = array_merge( $pageData, array( 'hub' => 'stuff' ) );
		$this->assertEquals(
				$expected,
				$service->execute()
		);
    }
    
    /**
     * @covers \Wikia\Search\IndexService\Redirects::execute
     */
    public function testRedirectsService() {
    	$mwservice = $this->service->setMethods( array( 'getGlobal', 'getRedirectTitlesForPageId' ) )->getMock();
    	$mwservice
    	    ->expects( $this->at( 0 ) )
    	    ->method ( 'getGlobal' )
    	    ->with   ( 'AppStripsHtml' )
    	    ->will   ( $this->returnValue( true ) )
    	;
    	$mwservice
    	    ->expects( $this->at( 1 ) )
    	    ->method ( 'getRedirectTitlesForPageId' )
    	    ->with   ( $this->pageId )
    	    ->will   ( $this->returnValue( array( 'foo', 'bar' ) ) )
    	;
    	$service = $this->getMockBuilder( '\Wikia\Search\IndexService\Redirects' )
    	                ->disableOriginalConstructor()
    	                ->setMethods( null )
    	                ->getMock();
    	$this->injectService( $service, $mwservice );
    	$service->setPageId( $this->pageId );
    	$this->assertEquals(
    			array( \Wikia\Search\Utilities::field( 'redirect_titles' ) => array( 'foo', 'bar' ) ),
    			$service->execute()
		);
    }
}