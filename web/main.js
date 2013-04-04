/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
var Microsoft = {

	// количество страниц
	pages: 0,
	// интервал между страницыми
	interval: 0,
	// сдвиг для страници
	shift: 0,
	// номер текущей страници
	current: 1,
	// облость скролинга
	scroll: null,
	// кнопки навигации
	nav: {
		previous: null,
		next: null
	},

	// инициализация
	init: function() {
		var pages = $('.b-page');

		this.nav.previous = $('.b-previous');
		this.nav.next     = $('.b-next');
		this.scroll       = $('.b-scroll');
		this.pages        = pages.size();
		this.interval     = parseInt(pages.css('margin-right'));
		this.shift        = parseInt(pages.css('width')) + this.interval;

		this.scroll.css({width: this.pages*this.shift});
		this.nav.previous.click(this.previous);
		this.nav.next.click(this.next);
		this.checkPositionNav();

		/*$('.b-user').click(function() {
			var checkbox = $(this).find('input');
			checkbox.prop('checked', !checkbox.prop('checked'));
			return false;
		});*/
		// Выделить все или ничего
		$('.b-select-control .b-select-all').click(function() {
			$('.b-scroll input:checkbox').prop('checked', true);
			return false;
		});
		$('.b-select-control .b-select-none').click(function() {
			$('.b-scroll input:checkbox:checked').prop('checked', false);
			return false;
		});
	},

	// обновление ленты
	update: function() {
		$.ajax({
			url: '/?action=update',
			async: true,
			dataType: 'json',
			statusCode: {
				200: function(data) {
					console.log(data);
				}
			}
		});
	},

	// перейти к предыдущей странице
	previous: function() {
		if (Microsoft.current > 1) {
			Microsoft.scroll.animate({
				'margin-left': ((Microsoft.current-1) * Microsoft.shift + (Microsoft.interval / 2)) * -1
			}, 150).animate({
				'margin-left': ((--Microsoft.current-1) * Microsoft.shift) * -1
			}, 450);
		}
		Microsoft.checkPositionNav();
		return false;
	},

	// перейти к следующей странице
	next: function() {
		if (Microsoft.current < Microsoft.pages) {
			Microsoft.scroll.animate({
				'margin-left': ((Microsoft.current-1) * Microsoft.shift - (Microsoft.interval / 2)) * -1
			}, 150).animate({
				'margin-left': (Microsoft.current++ * Microsoft.shift) * -1
			}, 450);
		}
		Microsoft.checkPositionNav();
		return false;
	},
	checkPositionNav: function() {
		Microsoft.nav.previous.removeClass('b-disabled');
		Microsoft.nav.next.removeClass('b-disabled');
		if (Microsoft.current == 1) {
			Microsoft.nav.previous.addClass('b-disabled');
		}
		if (Microsoft.current == Microsoft.pages) {
			Microsoft.nav.next.addClass('b-disabled');
		}
	}
};


$(function(){
	Microsoft.init();
});