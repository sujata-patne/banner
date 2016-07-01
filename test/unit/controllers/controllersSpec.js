//
// test/unit/controllers/controllersSpec.js
//

describe("Unit: Testing Controllers", function() {

  beforeEach(angular.mock.module('App'));

  it('should have a VideosCtrl controller', function() {
    expect(App.VideosCtrl).not.to.equal(null);
  });



});
