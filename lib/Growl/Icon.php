<?php
/**
 * Copyright (c) 2009-2013, Laurent Laville <pear@laurent-laville.org>
 *                          Bertrand Mansion <bmansion@mamasam.com>
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the authors nor the names of its contributors
 *       may be used to endorse or promote products derived from this software
 *       without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PHP version 5
 *
 * @category Networking
 * @package  Net_Growl
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version  SVN: $Id: Icon.php 329331 2013-01-29 13:18:57Z farell $
 * @link     http://growl.laurent-laville.org/
 * @link     http://pear.php.net/package/Net_Growl
 * @since    File available since Release 2.7.0
 */

/**
 * Icon resource
 *
 * @category Networking
 * @package  Net_Growl
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version  Release: 2.7.0
 * @link     http://growl.laurent-laville.org/
 * @link     http://pear.php.net/package/Net_Growl
 * @since    Class available since Release 2.7.0
 */
class Net_Growl_Icon
{
    /**
     * @var string
     */
    protected $iconData;

    /**
     * Constructor.
     *
     * @param string   $url            URL of icon file to read
     * @param bool     $useIncludePath (optional) Trigger include path search
     * @param resource $context        (optional) A valid context resource
     *                                 created with stream_context_create()
     *
     * @return Net_Growl_Icon
     */
    public function __construct($url, $useIncludePath = false, $context = null)
    {
        if (is_resource($context)) {
            $data = file_get_contents($url, false, $context);
        } else {
            if (strpos($url, '://') !== false) {
                $useIncludePath = false;
            }
            $data = file_get_contents($url, $useIncludePath);
        }

        $this->iconData = ($data === false) ? '' : $data;
    }

    /**
     * Return binary data of icon resource
     *
     * @return string
     */
    public function getContents()
    {
        return $this->iconData;
    }

}
