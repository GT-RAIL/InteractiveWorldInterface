InteractiveWorldInterface Build Setup
=====================================

[Phing](http://www.phing.info/) is used for documenting linting of PHP files.

### Install Phing

#### Ubuntu

 1. Install Phing and PHP CodeSniffer
   * `sudo pear channel-discover pear.phing.info`
   * `sudo pear install phing/phing PHP_CodeSniffer cakephp/CakePHP_CodeSniffer`
 4. (Optional) To generate the documentation, you'll need to setup phpDocumentor 2. Documentation generation is not required for patches.
   * `sudo apt-get install php5-xsl`
   * `sudo pear channel-discover pear.phpdoc.org`
   * `sudo pear install phpdoc/phpDocumentor`

### Build with Phing

Before proceeding, please confirm you have installed the dependencies above.

To run the build tasks:

 1. `cd /path/to/InteractiveWorldInterface/`
 2. `phing`

`phing build` will run the linters. This is what [Travis CI](https://travis-ci.org/WPI-RAIL/rms) runs when a Pull Request is submitted.

`phing doc` will document all PHP and JavaScript files and place them in the `doc` folder.