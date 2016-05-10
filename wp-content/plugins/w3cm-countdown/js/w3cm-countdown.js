localization = {
	en_US: {
		plural: function(word, num) {
			var forms = this[word].split('_');
			return num === 1 ? forms[0] : forms[1];
		},
		year: 'year_years',
		month: 'month_months',
		day: 'day_days',
		hour: 'hour_hours',
		minute: 'minute_minutes',
		second: 'second_seconds',
		millisecond: 'm.second_m.seconds'
	},
	nl_NL: {
		plural: function(word, num) {
			var forms = this[word].split('_');
			return num === 1 ? forms[0] : forms[1];
		},
		year: 'jaar_jaren',
		month: 'maand_maanden',
		day: 'dag_dagen',
		hour: 'uur_uren',
		minute: 'minuut_minuten',
		second: 'seconde_seconden',
		millisecond: 'm.seconde_m.seconden'
	},
	ru_RU: {
		plural: function(word, num) {
			var forms = this[word].split('_');
			return num % 10 === 1 && num % 100 !== 11 ? forms[0] : (num % 10 >= 2 && num % 10 <= 4 && (num % 100 < 10 || num % 100 >= 20) ? forms[1] : forms[2]);
		},
		year: 'год_года_лет',
		month: 'месяц_месяца_месяцев',
		day: 'день_дня_дней',
		hour: 'час_часа_часов',
		minute: 'минута_минуты_минут',
		second: 'секунда_секунды_секунд',
		millisecond: 'м.секунда_м.секунды_м.секунд'
	}
};


Date.prototype.getDaysInMonth = function(){
    var d= new Date(this.getFullYear(), this.getMonth()+1, 0);
    return d.getDate();
};

CountdownTimer = function(element, to) {
	element = document.getElementById(element);
	to      = new Date(to);
	if(element && to) {
		CountdownTimer.elements.push([element, to]);
	}
	return CountdownTimer.elements;
};

CountdownTimer.elements = [];

CountdownTimer.start = function() {
	CountdownTimer.elements.forEach(function(item){
		CountdownTimer.draw(item[0], item[1], item[2]);
	});
	setTimeout(function(){CountdownTimer.start();}, 100);
};

CountdownTimer.draw = function(element, to) {
	var now = new Date(),
		lang = localization[CountdownTimer.locale || 'en'];
	if((to - now) > 0) {
		var delta = [
				[to.getFullYear() - now.getFullYear(), 3000, 'year'],
				[to.getMonth() - now.getMonth(), 12, 'month'],
				[to.getDate() - now.getDate(), now.getDaysInMonth(), 'day'],
				[to.getHours() - now.getHours(), 24, 'hour'],
				[to.getMinutes() - now.getMinutes(), 60, 'minute'],
				[to.getSeconds() - now.getSeconds(), 60, 'second'],
				[to.getMilliseconds() - now.getMilliseconds(), 1000, 'millisecond']
			],
			block = '<div>{digit}</div><div>{digit}</div><span>{caption}</span>';
		for(var i = 6; i >= 0; i--) {
			if(i > 0 && delta[i][0] < 0) {
				delta[i][0] += delta[i][1];
				delta[i-1][0]--;
			}
			delta[i][2] = lang.plural(delta[i][2], delta[i][0]);
			delta[i][0] = ('0'+delta[i][0]).slice(-2);
		}
		while(delta.length > 3 && '00' === delta[0][0]) {
			delta.shift();
		}
		for(var i = 0; i < 3; i++) {
			element.children[i+1].innerHTML = block.replace('{caption}', delta[i][2]).
													replace('{digit}', delta[i][0][0]).
													replace('{digit}', delta[i][0][1]);
		}
	}else{
		element.innerHTML = '';
	}
};

window.onload=function(){
	CountdownTimer.start();
};