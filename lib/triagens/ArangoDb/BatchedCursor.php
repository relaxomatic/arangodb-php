<?php
namespace triagens\ArangoDb;
class BatchedCursor extends Cursor
{

    private $_processed = 0;
    public function setResults( array $data )
    {
        $this->_result=[];
        $this->add( $data );
        $this->rewind();
        $this->updateLength();
    }

    public function fetchNextBatch()
    {
        // continuation
        $this->_processed += $this->_length;
        $response = $this->_connection->put($this->url() . '/' . $this->_id, '', array());
        ++$this->_fetches;

        $data = $response->getJson();

        $this->_hasMore = (bool) $data[Cursor::ENTRY_HASMORE];
        $this->setResults($data[Cursor::ENTRY_RESULT]);

        if (!$this->_hasMore) {
            // we have fetched the complete result set and can unset the id now
            $this->_id = null;
        }
        return $this->_result;
    }

    public function next()
    {
        parent::next();
        if( $this->key() >= $this->_length && $this->_hasMore )
        {
            $this->fetchNextBatch();
        }
    }

    public function fullKey()
    {
        return $this->_processed + $this->key();
    }
}
?>