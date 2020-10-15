jQuery( window ).on( 'elementor/frontend/init', () => {

  const initialize_maps  = function( $scope, $ ) {
    $('.fs-widget-leaflet-map-playlist').each(function(){
      if ( !$(this).hasClass('fs-playlist-loaded') ) {

        $(this).addClass('fs-playlist-loaded');
        var id = $(this).attr('id');
        if (id !== undefined){
          // init vars
          var lat = $(this).attr('data-map-lat') || 50.6333;
          var lon = $(this).attr('data-map-lon') || 3.0619;
          var zoom = $(this).attr('data-zoom') || 13;
          var style = $(this).attr('data-map-design') || 'osm';
          var fit_bounds = 'yes';
          var marker_numbering = $(this).attr('data-marker-numbering');

          var elementorMap = L.map(id).setView([lat,lon],zoom);
          elementorMap.scrollWheelZoom.disable();
          elementorMap.gestureHandling.enable();
          elementorMap.dragging.enable();

          switch (style){
            case 'google-roadmap' :
              var layer = new L.Google('ROADMAP');
              break;
            case 'google-satellite' :
              var layer = new L.Google('');
              break;
            case 'google-terrain' :
              var layer = new L.Google('TERRAIN');
              break;
            case 'google-hybrid' :
              var layer = new L.Google('HYBRID');
              break;
            case 'mapbox-street':
              var layer = L.tileLayer('https://a.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                  attribution: '',
                  id: 'mapbox.streets',
                  accessToken: 'pk.eyJ1IjoiZmFpcmUtc2F2b2lyIiwiYSI6ImNqcDQ3cTdqOTAxcGMzeG1ranV2NDlvb28ifQ.J-08viX3_VpEhkEg97VB0g',
                  maxZoom: 18,
              });
              break;
            case 'mapbox-satellite':
              var layer = L.tileLayer('https://a.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                  attribution: '',
                  id: 'mapbox.satellite',
                  accessToken: 'pk.eyJ1IjoiZmFpcmUtc2F2b2lyIiwiYSI6ImNqcDQ3cTdqOTAxcGMzeG1ranV2NDlvb28ifQ.J-08viX3_VpEhkEg97VB0g',
                  maxZoom: 18,
              });
              break;
            case 'osm':
              var layer = L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                  attribution: '',
                  maxZoom: 19
              });
              break;
          }
          elementorMap.addLayer(layer);

          var markers_array = [];
          var markers_group = L.featureGroup();
          $(this).find('.point').each(function(idx, obj){

            var marker_id = $(this).attr('data-id');
            var marker_lat = $(this).attr('data-lat');
            var marker_lon = $(this).attr('data-lon');
            var marker_style = $(this).attr('data-marker');


            if (marker_id !== '' && marker_lat !== '' && marker_lon !== ''){
              var popup = $(this).html()

              var iconDiv = L.divIcon({
                iconSize:[48,58],
                iconAnchor:[24,58],
                popupAnchor:[0,-60],
                className:'marker marker-id-'+marker_id+' '+((marker_style != '' && marker_style != undefined)?'marker-'+marker_style:''),
                html:'<div class="pin"><span class="content">'+((marker_numbering == 'yes')?marker_id:'')+'<span></div>'
              })

              var marker = new L.marker([marker_lat,marker_lon],{
                icon : iconDiv,
              });
              if (popup !== undefined){
                marker.bindPopup(popup);
              }
              markers_array.push(marker);
              marker.addTo(markers_group);
            }
          });
          elementorMap.addLayer(markers_group);

          if(markers_array.length && fit_bounds == 'yes'){
            var corners = markers_group.getBounds();
            elementorMap.fitBounds(corners,{padding: [50,50]});
          }
        }
      }

    });
  };
  elementorFrontend.hooks.addAction( 'frontend/element_ready/fs-widget-playlist.default', initialize_maps );
} );

jQuery(function($){
  $(document).ready(function(){
    $('.see_more_offers').click(function(ev){
      ev.preventDefault();
      $(this).closest('.list').find('.more_offers').toggle('slow');
      if($(this).val() == 'J\'en veux plus'){
        $(this).val('J\'en veux moins');
      }else {
        $(this).val('J\'en veux plus');
      }
    });
  });
  $(window).load(function(){
    $('.map_side').addClass('map_loaded');
  });
});
