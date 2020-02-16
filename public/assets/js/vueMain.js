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
	        weekList: [], // delete?
	        clientInfo: {},
	        cityInfo: {}, // city full info
	        citySearch: 'Katowice, pl',
	        citySearchHistory: [],
	        errorMsg: false,
	        appSpinner: true,
	        isLocal: false,
	        chartTime: [],
	        chartTemperature: [],
	        chartTemperFeels: [],
	        chartHumidity: [],
	        hourSlider: 18,
	        hourSlider2: 12,
	        chartT: 0
	      };
	    },
	    computed: {
	        weekByHour: function () {
	        	
	        	hourArr = [];
	        	this.forecastResult.forEach((res) => {
	        		let formattedNumber = ("0" + this.hourSlider2).slice(-2);
	        		if (res.dt_txt.substring(11) == formattedNumber + ':00:00') {
	        			hourArr.push(res);
		        	}
	        	 });
	        	return hourArr;	        	       	
	        }
	    },
	    created() {
	        console.log('created called.');
	        this.setIsLocal();
	        this.getClientInfo();
	        this.loadCitySearchHistory();
	        this.initSlider();

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
	    	   },
	    },	    	  
	    methods: {
	    	initSlider() {
		    	  self = this;
		    	  setTimeout(function(){ 
		    		  
				        $("#rangesliderHours2").bootstrapSlider({
				        	value: self.hourSlider2,
				        	tooltip_position:'bottom',
				    		tooltip: 'always',
				    		formatter: function(value) {
				    			return 'Hour: ' + value + ':00';
				    		},

				    	}).on('slideStop', function(ev){
				    		//console.log('slideStop', ev.value, ev);
				    		self.hourSlider2 = ev.value;
				    	  });
		    		  
		    	  }, 1000);
		    	  

			        
		      },
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
	        			  //res.icon = res.weather[0].icon; // old
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
		        		  		        	  
			        	  let hour = vm.hourSlider;
			        	  vm.forecastResult = response.data.list;
			        	  
			        	  //vm.weekList = vm.filterByHour(response.data.list, hour);	        	  		        	  
			        	  //vm.forecastResult = hourArr; // old 	  		        	 
			        	  //vm.weekList = hourArr; 	  		        	 
			        	  vm.appSpinner = false;
			        	  // city
			        	  let ar = [];
			        	  ar.push(response.data.city);
			        	  vm.cityInfo = ar; 
			        	  
			        	  vm.chartTemperature = [];
			        	  vm.chartTemperFeels = [];
			        	  vm.chartHumidity = [];
			        	  vm.chartTime = [];
			        	  
			        	  response.data.list.forEach((res) => {
			        		  date = new Date(res.dt_txt);
			        		  vm.chartTemperature.push({x: date, y: res.main.temp});
			        		  vm.chartTemperFeels.push({x: date, y: res.main.feels_like});
			        		  vm.chartHumidity.push({x: date, y: res.main.humidity});
			        		  vm.chartTime.push(res.dt_txt);
				    	 });
			        	  
			        	  //vm.citySearchHistory.push(response.data.city);
			        	  vm.updateCitySearchHistory();
			        	  
			        	  vm.updateChartTemp();
		        		  
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
			    	updateChartTemp() {
			    		let timeFormat = 'YYYY-MM-DD';
			    		let config = {
			        	        type:    'line',
			        	        labels: this.chartTime,
			        	        //maintainAspectRatio: true,
			        	        data:    {
			        	            datasets: [
			        	            	{
			        	            		label: "Temprature",
			        	            		data: this.chartTemperature,
			        	            		fill: true,
			        	            		borderColor: 'blue',
			        	                    fillColor : "rgba(220,280,220,0.5)",
			        	                    strokeColor : "rgba(220,220,220,1)",
			        	                    borderWidth: 1,
			        	                    backgroundColor: "rgba(75,192,192,0.4)",
			        	            	},
			        	            	{
			        	            		label: "Feels like",
			        	            		data: this.chartTemperFeels,
			        	            		fill: false,
			        	            		borderColor: 'DARKBLUE',
			        	            		borderWidth: 0.4,
			        	            		hidden: true,
			        	            	},
			        	              	{
			        	            		label: "Humidity",
			        	            		data: this.chartHumidity,
			        	            		//fill: false,
			        	            		borderColor: 'yelloy',
			        	            		borderWidth: 0.2,
			        	            		yAxisID: 'H',
			        	            	},			        	            	
			        	            ]
			        	        },
			        	        options: {
			        	            responsive: true, 
			            	        maintainAspectRatio: false,
			        	            title:      {
			        	                display: true,
			        	                text:    "Temprature & Humidity"
			        	            },
			        	            scales:  {
			        	            	   xAxes: [{
			        	                       type:       "time",
			        	                       time:       {
			        	                       parser: timeFormat,
			        	                       tooltipFormat: 'YYYY-MM-DD HH:mm',
			        	                           displayFormats: {
			        	                               millisecond: 'HH:mm:ss.SSS',
			        	                               second: 'HH:mm:ss',
			        	                               minute: 'DD HH:mm',
			        	                               hour: 'DD HH',
			                                           day: 'MMM DD',
			                                           week: 'MM DD',
			                                           month: 'MMM DD',
			                                           quarter: 'MMM YYYY',
			        	                           }
			        	                       },
			        	                       scaleLabel: {
			        	                           display:     true,
			        	                           labelString: 'Date'
			        	                       },
			        	               }],
			        	                yAxes: [
			        	                	{
			        	                    scaleLabel: {
			        	                        display:     true,
			        	                        labelString: 'Temperature'
			        	                    }
			        	                	},
			        	                 	{
			            	                    scaleLabel: {
			            	                        display:     true,
			            	                        labelString: 'Humidity'
			            	                    },
				        	                    id: 'H',
				        	                    type: 'linear',
				        	                    position: 'right',
			            	                }
			        	                ]
			        	            }
			        	        }
			        	    }; // config
			    		

			    		let ctx = $('#chartMain');
			    		
			    		if (this.chartT != 0) {		    			
			    			this.chartT.data.datasets = config.data.datasets;
			    			this.chartT.update();
			    		
			    		} else {
			    			this.chartT  = new Chart(ctx, config);			    			
			    		}
			    		
			    	},
			    	removeChartData() {
			    		this.chartT.data.labels.pop();
			    		this.chartT.data.datasets.forEach((dataset) => {
			    	        dataset.data.pop();
			    	    });
			    		this.chartT.update();
			    	},
	      }, // methods
	      mounted:function(){
	    	  console.log('__mounted');
	           }
	  }).$mount("#weatherWueApp");
    
});