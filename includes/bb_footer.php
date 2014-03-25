<script>

    $(document).ready(function(){    
      var wrapperWidth = $(window).width() - $("#bb-leftbar").width();
      $("#content-wrapper").width(wrapperWidth);
      blocksArrange();
			adjustSidebar();
			
    });

    $(window).load(function(){
      blocksArrange();
			adjustSidebar();
    });

    $(window).resize(function(){      
      blocksArrange();
			adjustSidebar();
			//mobile experience
			
	if (navigator.userAgent.match(/Android/i)
					|| navigator.userAgent.match(/webOS/i)
					|| navigator.userAgent.match(/iPhone/i)
					|| navigator.userAgent.match(/iPad/i)
					|| navigator.userAgent.match(/iPod/i)
					|| navigator.userAgent.match(/BlackBerry/i)
					|| navigator.userAgent.match(/Windows Phone/i)
					|| navigator.userAgent.match(/Opera Mini/i)
					|| navigator.userAgent.match(/IEMobile/i)
					) {
			isMobile = true;
	}else{
			isMobile = false;
	};
	if(isMobile == true){
		if(window.innerHeight > window.innerWidth){
				blocksArrange();
			adjustSidebar();
		}else{
				blocksArrange();
			adjustSidebar();
		};
	};
			
    });
		
		$( window ).scroll(function() {
			adjustSidebar();
		});
		
		function adjustSidebar(){
			$("#bb-leftbar").height('100%');
			if($("#content-wrapper").height() > $(window).height()){
				$("#bb-leftbar").height($("#content-wrapper").height());
			}else{
				$("#bb-leftbar").height('100%');
			};
		};

    function blocksArrange(){

			//sidebar
			if(window.innerHeight < 995){
				$(".s-contact-details").css({"position": "relative", "float": "left", "display": "block","margin-top": "-71px"});
			}else{
				$(".s-contact-details").css({"position": "fixed"});
			};


      if($(window).width() < <?php echo get_option('bb_tiles_breakpoint1') + 1; ?>){
        $("#bb-leftbar").width(<?php echo get_option('bb_tiles_sidebar_width_mobile'); ?>);
        var wrapperWidth = $(window).width() - $("#bb-leftbar").width();
        $("#content-wrapper").width(wrapperWidth);
        var dividebywidth = 1;
      }else if($(window).width() < <?php echo get_option('bb_tiles_breakpoint2'); ?>){
        var dividebywidth = 1;
      }else if($(window).width() < <?php echo get_option('bb_tiles_breakpoint3'); ?>){
        var dividebywidth = 2;
      }else if($(window).width() < <?php echo get_option('bb_tiles_breakpoint4'); ?>){
        var dividebywidth = 3;
      }else{
        var dividebywidth = 4;
      }

				//if($('.subpage')){
//					dividebywidth = 5;
//				};

			
			if($(window).width() > <?php echo get_option('bb_tiles_breakpoint1'); ?>){
				$("#bb-leftbar").width(<?php echo get_option('bb_tiles_sidebar_width_large'); ?>);
			};
			
			
			$(".isotope-item").width(($("#content-wrapper").width() / dividebywidth) - 3);
				$("#container").isotope('reLayout');
			};

    $(function(){
    
      var $container = $('#container');
    
      $container.isotope({
        
        sortBy: 'original-order',
        getSortData: {
          number: function( $elem ) {
            var number = $elem.hasClass('element') ? 
              $elem.find('.number').text() :
              $elem.attr('data-number');
            return parseInt( number, 10 );
          },
          alphabetical: function( $elem ) {
            var name = $elem.find('.name'),
                itemText = name.length ? name : $elem;
            return itemText.text();
          }
        }
      });
          
      var $optionSets = $('#options .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        var $this = $(this);
        // don't proceed if already selected
        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  
        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
          // changes in layout modes need extra logic
          changeLayoutMode( $this, options )
        } else {
          // otherwise, apply new options
          $container.isotope( options );
        }
        
        return false;
      });
    
      // Sites using Isotope markup
      var $sites = $('#sites'),
          $sitesTitle = $('<h2 class="loading"><img src="http://i.imgur.com/qkKy8.gif" />Loading sites using Isotope</h2>'),
          $sitesList = $('<ul class="clearfix"></ul>');
        
      $sites.append( $sitesTitle ).append( $sitesList );

      $sitesList.isotope({
        layoutMode: 'cellsByRow',
        cellsByRow: {
          columnWidth: 290,
          rowHeight: 400
        }
      });
    
      var ajaxError = function(){
        $sitesTitle.removeClass('loading').addClass('error')
          .text('Could not load sites using Isotope :(');
      };
			
			var wrapperWidth = $(window).width() - $("#bb-leftbar").width();
      $("#content-wrapper").width(wrapperWidth);
      
    
    });
		
		$(window).resize(function(){
			var wrapperWidth = $(window).width() - $("#bb-leftbar").width();
      $("#content-wrapper").width(wrapperWidth);
		});
  </script>