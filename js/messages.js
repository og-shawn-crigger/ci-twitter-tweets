/*
 * @author http://seanmonstar.com/post/707205823/fade-and-destroy-elements
 */
Element.implement({
	fadeAndDestroy: function(duration) {
		duration = duration || 600;
		var el = this;
		this.set('tween', {
			duration: duration
			}).fade('out').get('tween').chain(function() {
				el.dispose();
		});
	}
});
document.addEvent('domready', function() {
	// message
	var message =  document.getElement('.success') || document.getElement('.error')

	// after x time remove message element.
	if (message) {
			message.fadeAndDestroy(1200);
	}
});