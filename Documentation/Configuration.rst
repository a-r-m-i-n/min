.. include:: Includes.txt


.. _configuration:


Configuration
=============

By default, when you've applied the EXT:min TypoScript configuration, all minification features are enabled
and pre-configured to get best compression results.

New in TYPO3 v13
----------------

Now that you can use site sets in your projects. You may only want to include the minification of assets
or the tinysourcing of the html output and enabling the debugging options only when needed.

Therefore the pre configured site sets are now available in your site settings in the backend.


Debugging
---------

Also, there is a TypoScript condition included which takes effect, when

- you are logged-in to the TYPO3 backend and
- added the query parameter ``?debug=1``

This disables all features of EXT:min and also the compression and concatenation features of the core itself.


Settings
--------

The following chapters, explain the available options you have, using the default configuration as example.


Asset compression (CSS/JS)
--------------------------

To enable the asset compression of TYPO3, as well as of EXT:min just add this to your TypoScript.

.. code-block:: typoscript

	config {
		compressCss = 1
		concatenateCss = 1
		compressJs = 1
		concatenateJs = 1
	}


AssetCollector compression
--------------------------

Since version 2.1 assets added by the ``AssetCollector`` are also minified (but not concatenated).
You can enable/disable the compression of AssetCollector assets. By default it is enabled:


.. code-block:: typoscript

	plugin.tx_min.assetCollector {
		compressCss = 1
		compressInlineCss = 1
		compressJs = 1
		compressInlineJs = 1
	}


HTML source compression
-----------------------

Because of historical reasons, the configuration for HTML compression is located in **plugin.tx_min.tinysource**:

.. code-block:: typoscript

	plugin.tx_min.tinysource {
		enable = 1
		body {
			stripComments = 1
			preventStripOfSearchComment = 1
			removeTypeInScriptTags = 1
		}
		protectCode {
			10 = /(<textarea.*?>.*?<\/textarea>)/is
			20 = /(<pre.*?>.*?<\/pre>)/is
		}
	}


===================================== ============ ======================================
Property                               Type         Default
===================================== ============ ======================================
enable_                                ``bool``     1
body.stripComments_                    ``bool``     1
body.preventStripOfSearchComment_      ``bool``     1
body.removeTypeInScriptTags_           ``bool``     1
protectCode_                           ``array``    *see below*
===================================== ============ ======================================

.. _enable:

enable
""""""
.. container:: table-row

   Property
      enable
   Data type
      bool
   Default
      1
   Description
      Enables or disables the HTML source compression.


.. _body.stripComments:

body.stripComments
""""""""""""""""""
.. container:: table-row

   Property
      body.stripComments
   Data type
      bool
   Default
      1
   Description
      Removes all HTML comments from HTML body, when enabled.


.. _body.preventStripOfSearchComment:

body.preventStripOfSearchComment
""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
      body.preventStripOfSearchComment
   Data type
      bool
   Default
      1
   Description
      Prevents ``<!--TYPO3SEARCH_begin-->`` and ``<!--TYPO3SEARCH_end-->`` from being removed, when **stripComments** setting is enabled.


.. _body.removeTypeInScriptTags:

body.removeTypeInScriptTags
"""""""""""""""""""""""""""
.. container:: table-row

   Property
      body.removeTypeInScriptTags
   Data type
      bool
   Default
      1
   Description
      Removes ``type`` attribute from ``<script>`` tags, within body.


.. _protectCode:

protectCode
"""""""""""
.. container:: table-row

   Property
      protectCode
   Data type
      array
   Default
	  .. code-block:: typoscript

			10 = /(<textarea.*?>.*?<\/textarea>)/is
			20 = /(<pre.*?>.*?<\/pre>)/is

   Description
      Allows to protect whitespace sensitive code from being touched, by using regular expressions.
      ``textarea`` and ``pre`` tags are protected, by default.
