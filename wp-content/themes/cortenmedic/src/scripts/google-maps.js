import * as Utils from './utils';

(function($, window, document, undefined) {
  'use strict';

  class GMap {

    constructor() {
      this.map = document.getElementById('google-map');
    }

    init() {
      if( this.map != null ) {
        this.type     = this.map.getAttribute('data-map-type');
        this.apikey   = Utils.localized('gmapsapikey');
        this.mapId    = this.map.getAttribute('data-map-id');
        this.markerUrl = Utils.localized('mapmarkerurl');

        $.getScript(`https://maps.googleapis.com/maps/api/js?key=${this.apikey}`, (script, status, xhr) => {
          if(status == 'success') {
            this.createMap(this.type);
          }
        });
      }
    }

    initMap(type) {
      const mapstyle = [
        {
          "featureType": "all",
          "elementType": "all",
          "stylers": [
            {
                "saturation": "-100"
            }
          ]
        }
      ];
      
      const LngLat = new google.maps.LatLng('52.0693','19.4803'); //middle of Poland
			
			const mapOptions = {
				zoom: 17,
				center: LngLat,
        styles: mapstyle,
				mapTypeId: 'roadmap',
				disableDefaultUI:false,
				scrollwheel: false,	
			};

			this.googleMap = new google.maps.Map(this.map, mapOptions);
    }

    createMap(type) {
     const data = {
       action: 'get_map_data',
       nonce: Utils.localized('mapnonce'),
       map_id: this.mapId,
       type: type
     }

     $.post( Utils.localized('ajaxurl'), data )
      .done( (response) => {
        switch( response[0] ) {
          case 'ok':
            this.initMap();
            this.showMap(type, response[1]);
            break;
          default:
            console.log('map error!');
            break;
        }
      })
      .fail( (error) => {
        console.log(error)
      });
    }

    adjustViewPort(map) {
      google.maps.event.addListenerOnce(map, "projection_changed", () => {
        if (map.getProjection()) {
          let offsetx = Utils.isRwdSize('640') ? 0 : -1000;
          let offsety = 0;

          const point1 = map.getProjection().fromLatLngToPoint(this.bounds.getNorthEast());
          map.fitBounds(this.bounds);

          const point2 = new google.maps.Point(
              ( (typeof(offsetx) == 'number' ? offsetx : 0) / Math.pow(2, map.getZoom()) ) || 0,
              ( (typeof(offsety) == 'number' ? offsety : 0) / Math.pow(2, map.getZoom()) ) || 0
          );          

          const newPoint = map.getProjection().fromPointToLatLng(new google.maps.Point(
              point1.x - point2.x,
              point1.y + point2.y
          ));

          this.bounds.extend(newPoint);
          map.fitBounds(this.bounds);
        }
      });
    }

    findMarker(markers) {
      const searchForm = $('.institutions-map-search-form');

      if( !searchForm.length ) return;

      this.cityField        = searchForm.find('#mcity');
      this.institutionField = searchForm.find('#minstitution');

      const error = searchForm.find('.sff-error'),
        button = searchForm.find('.form-search-submit .btn');

      button.on('click', () => {
        error.hide();

        if( $.trim( this.cityField.val() ) == '' ) {
          this.cityField.closest('.form-group').find('.sff-error').show();  
        }else {
          if( $.trim( this.institutionField.val() ) == '' ) {
            this.institutionField.closest('.form-group').find('.sff-error').show();  
          }else {
            const id = $.trim( this.institutionField.val() );

            if( markers.length ) {
              $.each(markers, (index, marker) => {
                if( marker.id == id ) {
                  new google.maps.event.trigger( marker, 'click' );
                  return false;
                }
              });
            }
          }
        }

        this.cityField.on('change', () => {
          if( $.trim( this.cityField.val() ) != '' ) {
            error.hide();
          }
        });

        this.institutionField.on('change', () => {
          if( $.trim( this.institutionField.val() ) != '' ) {
            error.hide();
          }
        });
      });
    }

    showMap(type, coords) {
      if( type == 'single' ) {
        let LatLng = {lat: parseFloat(coords[0].lat), lng: parseFloat(coords[0].lng)}
        let marker = new google.maps.Marker({
          position: LatLng,
          map: this.googleMap,
          animation: google.maps.Animation.DROP,
          icon: this.markerUrl
        });
        
        this.googleMap.setCenter(LatLng);
      }else {
        const locations = [];
        for(let i=0; i<coords.length; i++) {
          locations.push(
            [
              coords[i].lat,
              coords[i].lng,
              coords[i].info,
              coords[i].boundsExclude,
              coords[i].id
            ]
          )
        }

        let infowindow = new google.maps.InfoWindow();
        
        let marker,
            markers = [];
            
        for (var i = 0; i < locations.length; i++) {  
          marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][0], locations[i][1]),
            map: this.googleMap,
            icon: this.markerUrl,
            boundsExclude: locations[i][3],
            id: locations[i][4]
          });
          markers.push(marker);
        }

        this.bounds = new google.maps.LatLngBounds();
				$.each(markers, (index, marker) => {
          if( marker.boundsExclude == 0 ) {
            this.bounds.extend(marker.position);
          }

          marker.addListener('click', () => {
            infowindow.open(this.googleMap, marker);
            infowindow.setContent(locations[index][2]);
            this.googleMap.setCenter(marker.position);
            this.googleMap.setZoom(16);
            if( Utils.isRwdSize('640') ) {
              this.googleMap.panBy(0,0);
            }else {
              this.googleMap.panBy(200,0);
            }
          });
        });
       
        this.adjustViewPort(this.googleMap)

        google.maps.event.addListener(infowindow, 'domready', () => {
          const iwOuter = $('.gm-style-iw');
          
          iwOuter.parent().addClass('gm-style-iw-parent');
          iwOuter.prev().hide().css({
            'background-color': 'rgba(255,255,255,0)',
          });
          iwOuter.next().addClass('map-info-close').html('<i class="fa fa-close fa-18"></i>');

          iwOuter.css({
            'opacity': 1
          });
				});

        google.maps.event.addListener(infowindow, 'closeclick', () => {
          this.cityField.selectpicker('val', '');
          this.cityField.selectpicker('refresh');

          this.institutionField.selectpicker('val', '');
          this.institutionField.prop('disabled', true);
          this.institutionField.selectpicker('refresh');

					this.googleMap.fitBounds(this.bounds);
        });
        
        this.findMarker(markers);

      }

    }

  }
  let gmaps = new GMap();
  gmaps.init();

})(jQuery, window, document);