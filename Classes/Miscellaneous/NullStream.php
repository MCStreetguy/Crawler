<?php

/**
 * This file is part of the mcstreetguy/crawler package.
 *
 * @package    mcstreetguy/crawler
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @version    SVN: $Id$
 */

namespace MCStreetguy\Crawler\Miscellaneous;

use Psr\Http\Message\StreamInterface;

/**
 * A class that emulates a normal readable and writeable stream.
 *
 * A class that emulates a normal readable and writeable stream, but does not write to or read from anywhere.
 * Writing to the stream causes it to confirm the operation but drop the data.
 * Reading from the stream returns nothing as it is always at the end (eof).
 * Seeking in the stream will raise an \RuntimeException as this is technically not possible.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class NullStream implements StreamInterface
{
    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * As the NullStream is a mocking object, this will always return an empty string.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString()
    {
        return '';
    }

    /**
     * Closes the stream and any underlying resources.
     *
     * As the NullStream is a mocking object, this won't do anything.
     *
     * @return void
     */
    public function close()
    {
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * As the NullStream is a mocking object, this won't do anything.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach()
    {
    }

    /**
     * Get the size of the stream if known.
     *
     * As the NullStream is a mocking object, this will always return '0'.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize()
    {
        return 0;
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * As the NullStream is a mocking object, this will always return '0'.
     *
     * @return int Position of the file pointer
     * @throws \RuntimeException on error.
     */
    public function tell()
    {
        return 0;
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * As the NullStream is a mocking object, this will always return 'true'.
     *
     * @return bool
     */
    public function eof()
    {
        return true;
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * As the NullStream is a mocking object, this will always return 'false'.
     *
     * @return bool
     */
    public function isSeekable()
    {
        return false;
    }

    /**
     * Seek to a position in the stream.
     *
     * As the NullStream is a mocking object, this will always throw a \RuntimeException.
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset. Valid values are identical to the built-in
     *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *     offset bytes SEEK_CUR: Set position to current location plus offset
     *     SEEK_END: Set position to end-of-stream plus offset.
     * @throws \RuntimeException on failure.
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        throw new \RuntimeException('Stream is not seekable!', 1553786797372);
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * As the NullStream is a mocking object, this will always throw a \RuntimeException.
     *
     * @see seek()
     * @link http://www.php.net/manual/en/function.fseek.php
     * @throws \RuntimeException on failure.
     */
    public function rewind()
    {
        throw new \RuntimeException('Stream is not seekable!', 1553786860096);
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * As the NullStream is a mocking object, this will always return 'true'.
     *
     * @return bool
     */
    public function isWritable()
    {
        return true;
    }

    /**
     * Write data to the stream.
     *
     * As the NullStream is a mocking object, this will always return the byte length of the input-string, as if the write was successful.
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws \RuntimeException on failure.
     */
    public function write($string)
    {
        return strlen($string);
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * As the NullStream is a mocking object, this will always return 'true'.
     *
     * @return bool
     */
    public function isReadable()
    {
        return true;
    }

    /**
     * Read data from the stream.
     *
     * As the NullStream is a mocking object, this will always return an empty string.
     *
     * @param int $length Read up to $length bytes from the object and return
     *     them. Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws \RuntimeException if an error occurs.
     */
    public function read($length)
    {
        return '';
    }

    /**
     * Returns the remaining contents in a string
     *
     * As the NullStream is a mocking object, this will always return an empty string.
     *
     * @return string
     * @throws \RuntimeException if unable to read or an error occurs while
     *     reading.
     */
    public function getContents()
    {
        return '';
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * As the NullStream is a mocking object, this will always return 'null'.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public function getMetadata($key = null)
    {
        return null;
    }
}
