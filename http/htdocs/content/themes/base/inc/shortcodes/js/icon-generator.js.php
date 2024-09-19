(function() {
	// Register buttons
	tinymce.create('tinymce.plugins.IconButton', {
		init: function( editor, url ) {
			// Add button that inserts shortcode into the current position of the editor
			editor.addButton( 'icon_button', {
				title: 'Icon Generator',
				icon: false,
				onclick: function() {
					// Open a TinyMCE modal
					editor.windowManager.open({
						title: 'Icon Shortcode Generator',
						body: [
							{
								type: 'listbox',
								name: 'icon',
								label: 'Select Icon',
								values: [
									{ text: 'Air Conditioning', value: 'amenity-ac' },
									{ text: 'Bed', value: 'amenity-bed' },
									{ text: 'Bed Sheets', value: 'amenity-bedsheets' },
									{ text: 'Clean', value: 'amenity-clean' },
									{ text: 'Clock', value: 'amenity-clock' },
									{ text: 'Clock 2', value: 'amenity-clock-2' },
									{ text: 'Coffee Machine', value: 'amenity-coffee' },
									{ text: 'Destination', value: 'amenity-destination' },
									{ text: 'Dining', value: 'amenity-dining' },
									{ text: 'Flower', value: 'amenity-flower' },
									{ text: 'Golf', value: 'amenity-golf' },
									{ text: 'Hairdryer', value: 'amenity-hairdryer' },
									{ text: 'HVAC', value: 'amenity-hvac' },
									{ text: 'Ice', value: 'amenity-ice' },
									{ text: 'Iron', value: 'amenity-iron' },
									{ text: 'Kitchen', value: 'amenity-kitchen' },
									{ text: 'Lock', value: 'amenity-lock' },
									{ text: 'Map Pin', value: 'map-pin' },
									{ text: 'Newspaper', value: 'amenity-newspaper' },
									{ text: 'Phone', value: 'phone' },
									{ text: 'Plane', value: 'amenity-plane' },
									{ text: 'Robes', value: 'amenity-robes' },
									{ text: 'Room Service', value: 'amenity-room-service' },
									{ text: 'Sofa Bed', value: 'amenity-sofa-bed' },
									{ text: 'Star', value: 'amenity-star' },
									{ text: 'Toiletries', value: 'amenity-toiletries' },
									{ text: 'Turndown', value: 'amenity-turndown' },
									{ text: 'TV', value: 'amenity-tv' },
									{ text: 'USB', value: 'amenity-usb' },
									{ text: 'Vanity Mirror', value: 'amenity-vanity' },
									{ text: 'Wifi', value: 'amenity-wifi' }
								]
							},
							{
								type:'listbox',
								name: 'colour',
								label: 'Icon Colour',
								values: [
									{ text: 'Black', value: 'black'},
									{ text: 'Green', value: 'green'}
								]
							},
							{
								type:'listbox',
								name: 'style',
								label: 'Content Style',
								values: [
									{ text: 'Bold', value: 'bold'},
									{ text: 'Normal', value: 'normal'}
								]
							},
							{
								type: 'textbox',
								name: 'link',
								label: 'Link'
							},
							{
								type: 'textbox',
								name: 'content',
								label: 'Content'
							},
							{
								type: 'textbox',
								name: 'alt',
								label: 'alt tag(for SEO purposes)'
							}
						],
						onsubmit: function( e ) {
							editor.insertContent( '[icon icon="' + e.data.icon + '" colour="' + e.data.colour + '" style="' + e.data.style + '" link="' + e.data.link + '" content="' + e.data.content + '" alt="' + e.data.alt + '"]' );
						}
					});
				}
			});
		}
	});
	// Add buttons
	tinymce.PluginManager.add( 'icon_button_script', tinymce.plugins.IconButton );
})();