AMF
===
This is a micro php-framework.
This framework was written for educational purpose. 
DO NOT USE IT IN YOUR PROJECTS! (Just in case, i don't think someone would :) )

=Usage=
  1. Copy ./amf/ folder to your project root.
  2. Include ./amf/core.php into your project file.
  3. Ready! core.php contains magic __autoload() method, so any of the classes from AMF you are using will be load automaticly.
  4. If you need to change directory structure of your project, be sure to redefine AMF_BASE_PATH before including "./amf/core.php" file.

=Files=

- ./amf/
  -=Framework source code=-
  - .htaccess - Apache configuration file
  - Application.php - HTTP application class
  - CacheProvider.php - CacheProvider interface
  - core.php - Framework core
  - FileCacheProvider.php - Filecache implementation of CacheProvider interface
  - HeaderWriter.php - Allows to send predefined HTTP headers (predefined headers for content-type html, json, xml and location: %url%)
  - Logger.php - Simple logger
  - Mailer.php - E-mail messages sending engine
  - MassTest.php - Masstest engine. Runs all the tests at specified path.
  - MemCacheProvider.php - Memcache implementation for CacheProvider interface
  - MySQLConnector.php - MySQL helper class
  - PageBuilder.php - Page building part of template engine
  - Templater.php - Template engine
  - TestCase.php - Unit testing class (strategy)
  
- ./test/
  -=Unit tests for framework modules=-
  AllTheTests.php - automatically runs all the unit test in this folder
  TestApplication.php - Application class unit test
  TestCache.php - CacheProvider unit test
  TestMailer.php - Mailer unit test
  TestPageBuilder.php - Template engine unit test
  TestTemplates.php - Template engine unit test
  TestTestCase.php - TestCase unit test
