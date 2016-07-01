module.exports = function() {
  return {
    basePath: '../',
    frameworks: ['mocha'],
    reporters: ['progress','html'],

  htmlReporter: {
      outputFile: 'tests/units.html',
			
      // Optional 
      pageTitle: 'Banner management',
      subPageTitle: 'Unit Tests results'
    },

    browsers: ['Chrome'],
    autoWatch: true,

    // these are default values anyway
    singleRun: false,
    colors: true,
    
    files : [
      //3rd Party Code
      //App-specific Code
      'app/assets/app.js',

      //Test-Specific Code
      'node_modules/chai/chai.js',
      'test/lib/chai-should.js',
      'test/lib/chai-expect.js'
    ]
  }
};
