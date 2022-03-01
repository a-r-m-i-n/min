.. include:: Includes.txt


.. _installation:


Installation
============

You can install EXT:min like any other TYPO3 extension.


Requirements
------------

- PHP 7.4 or higher
- TYPO3 10.4 or 11.5


Setup
-----

**Since version 2.0 it is required to manually include the TypoScript configuration, of EXT:min.**

You can do this in TypoScript using the ``@import`` function:

.. code-block:: typoscript

	@import 'EXT:min/Configuration/TypoScript/setup.typoscript'


or define it as static include in the template itself:

.. image:: Images/Installation_StaticIncludes.png
   :alt: Setup of EXT:min in TypoScript template's static includes
