/**
 * VUE Main
 */

var weatherWueApp;

Vue.config.debug = true;

Vue.filter('formatDateHM', function(value) {
	if (value) {
		return moment(parseInt(value)*1000).format('HH:mm')
	}
});

Vue.filter('formatDateDayName', function(value) {
	  if (value) {
		  return moment(value).format('dddd');
	  }
});


jQuery(document).ready(function () {

	
	 weatherWueApp = new Vue({
		 
	    data() {
	    	
	      return {
	        forecastResult: [],
	        clientInfo: {},
	        cityInfo: {}, // city
	        citySearch: 'Katowice, pl',
	        citySearchHistory: [],
	        errorMsg: false,
	        appSpinner: true,
	        isLocal: false
	      };
	    },
	    created() {
	        console.log('created called.');
	        this.setIsLocal();
	        this.getClientInfo();
	        this.loadCitySearchHistory();	        
	      },
	    watch: {
	    	  citySearch: function (val) {
	    		  console.log('watch  citySearch ', val);
	    		  if (val.length>3) {
	    			  this.searchForecast();	    			  
	    		  } else {
	    			  this.errorMsg = 'Wrong city name';
	    			  return false;
	    		  }
	    	   }
	    },	    	  
	    methods: {
	    	setIsLocal() {
	    		if (window.location.href.indexOf('weatherapp.lan')> 5) {
	    			this.isLocal = true;
	    		}
	    	},
	        searchButton: function () {
	          this.searchForecast();	      
	        },
	        filterByHour: function(data, hour) {
	        	hourArr = [];
	        	 data.forEach((res) => {
	        		  if (res.dt_txt.substring(11) == hour + ':00:00') {
	        			  res.icon = res.weather[0].icon;
		        		  hourArr.push(res);
		        	  }
	        	 });
	        	 return hourArr;
	    	},
	    	
	    	searchCity(city) {
	    		this.citySearch = city;
	    		return this;
	    	},
	    	
	    	loadCitySearchHistory: function() {
	    		//console.log('loadCitySearchHistory');
	    		let cookie = Cookies.get('cityhistory');
	    		if (cookie != undefined && cookie.length > 0) {
	    			cityhistory = $.parseJSON(cookie);
	    			if (cityhistory != undefined && cityhistory.length > 0) {
	    				this.citySearchHistory = cityhistory;
	    			} 
	    		}
	    	},
	    	
	    	updateCitySearchHistory: function() {
	    		
	    		// $.parseJSON(Cookies.get('info'))
	    		//console.log('updateCitySearchHistory citySearchHistory ', this.citySearchHistory);
	    		let exist = false;
	    		this.citySearchHistory.forEach((res) => {
	    			if (res.id == this.cityInfo[0].id) {
	    				exist = true;;
	    			}
	    	 	});
	    		if (exist == true) {
	    			return false;
	    		}
	    		//console.log('updateCitySearchHistory  ', this.cityInfo[0].name);
	    		this.citySearchHistory.push(this.cityInfo[0]);
	    		Cookies.set('cityhistory', JSON.stringify(this.citySearchHistory), { expires: 365 });
	    		return false;
	    	},
	        searchForecast: function () {
		          
		          var vm = this;
		          let city = this.citySearch;
		          console.log('forecast: ' + city);
		          vm.errorMsg = false;
		          		          
		          axios.get('/mapapi/forecastcity?city='+city)
		          .then(function (response) {
		        	  //console.log(response.data);
		        	  if (response.data.cod == "200") {
		        		  
			        	  let hourArr = [];
			        	  let hour = 12;		        	  
			        	  hourArr = vm.filterByHour(response.data.list, hour);	        	  		        	  
			        	  vm.forecastResult = hourArr; 	  		        	 
			        	  vm.appSpinner = false;
			        	  // city
			        	  let ar = [];
			        	  ar.push(response.data.city);
			        	  vm.cityInfo = ar; 
			        	  
			        	  //vm.citySearchHistory.push(response.data.city);
			        	  vm.updateCitySearchHistory();
		        		  
		        	  } else if (response.data.cod == "404") {
						vm.errorMsg = 'Error: ' + response.data.message;
					} else {
						vm.errorMsg = 'Error: ';
					}
		          })
		          .catch(function (error) {
		            console.log(error);
		          })
		          .then(function () {
		        	  // always
		          });
		     
		        },
		        getClientInfo: function () {

			         var vm = this;
			         let cookie = Cookies.get('info');
			          
			         if (cookie != undefined && cookie.length > 10) {        		
			         		info = $.parseJSON(cookie);
			         		vm.clientInfo = info;
				        	//console.log('clientInfo COOKIE!', info);
			         		jQuery('#navbarSearchInput').val(this.getClientInfoLocation());
			         		this.citySearch = this.getClientInfoLocation();
				        	return info;
			         		
			        }
			          axios.get('https://ipinfo.io')
			          .then(function (response) {
			        	  let info = {};
			        	  info.ipinfo = response.data;
			        	  vm.clientInfo = info;
			        	  Cookies.set('info', JSON.stringify(info), { expires: 365 });
			        	  jQuery('#navbarSearchInput').val(vm.getClientInfoLocation());
			        	  vm.citySearch = vm.getClientInfoLocation();			        	  
			          })
			          .catch(function (error) {
			            console.log(error);
			            vm.citySearch = "Miechowice, pl";
			          })
			          .then(function () {
			            // always 
			          });
			        },
			        getClientInfoLocation() {
			    		
			        	let str = this.clientInfo.ipinfo.city;			        	
			        	if (this.clientInfo.ipinfo.country) {
			        		str += ', ' + this.clientInfo.ipinfo.country; 
			        	}
			        	return str;
			    		
			    	},
	      }, // methods
	      mounted:function(){
	    	  console.log('__mounted');
	           }
	  }).$mount("#weatherWueApp");
    
});