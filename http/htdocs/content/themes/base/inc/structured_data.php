<?php /* === Schema - Google Structured Data === */

/* === Structured Data Calls === */
$schemas = !empty(get_field('schemas')) ? get_field('schemas') : '';
if ($schemas) {
	$schema_full = '';
	foreach ($schemas as $schema) {
		$schema_full .= PHP_EOL.'<script type="application/ld+json">';
		$type = $schema['type'];
		switch ($type) {
			case 'Resort':
				$schema_full .= get_schema_resort($schema['resort_schema']);
				break;
            case 'Hotel':
                $schema_full .= get_schema_hotel($schema['hotel_schema']);
                break;
			case 'Organization':
				$schema_full .= get_schema_organization($schema['organization_schema']);
				break;
			case 'Offer':
				$schema_full .= get_schema_offer($schema['offer_schema']);
				break;
			case 'Contact':
				$schema_full .= get_schema_contact($schema['contact_schema']);
				break;
			case 'Park':
				$schema_full .= get_schema_park($schema['park_schema']);
				break;
			case 'HotelRoom':
				$schema_full .= get_schema_hotelroom($schema['hotelroom_schema']);
				break;
			case 'Restaurant':
				$schema_full .= get_schema_restaurant($schema['restaurant_schema']);
				break;
			case 'BarOrPub':
				$schema_full .= get_schema_barorpub($schema['barorpub_schema']);
				break;
			case 'CafeOrCoffeeShop':
				$schema_full .= get_schema_cafeorcoffeeshop($schema['cafeorcoffeeshop_schema']);
				break;
			case 'LocalBusiness':
				$schema_full .= get_schema_localbusiness($schema['localbusiness_schema']);
				break;
			case 'Event':
				$schema_full .= get_schema_event($schema['event_schema']);
				break;
			case 'FAQ':
				$schema_full .= get_schema_faq($schema['faq_schema']);
				break;
			case 'ProgramMembership':
				$schema_full .= get_schema_program_membership($schema['program_membership_schema']);
				break;
			case 'EventVenue':
				$schema_full .= get_schema_eventvenue($schema['eventvenue_schema']);
				break;
			case 'DaySpa':
				$schema_full .= get_schema_dayspa($schema['dayspa_schema']);
				break;
			case 'MeetingRoom':
				$schema_full .= get_schema_meetingroom($schema['meetingroom_schema']);
				break;
			case 'ShoppingCenter':
				$schema_full .= get_schema_shoppingcenter($schema['shoppingcenter_schema']);
				break;
			case 'GolfCourse':
				$schema_full .= get_schema_golfcourse($schema['golfcourse_schema']);
				break;
			case 'Accommodation':
				$schema_full .= get_schema_accommodation($schema['accommodation_schema']);
				break;
			case 'Suite':
				$schema_full .= get_schema_suite($schema['suite_schema']);
				break;
			case 'Product':
				$schema_full .= get_schema_product($schema['product_schema']);
				break;
			case 'SportsClub':
				$schema_full .= get_schema_sportsclub($schema['sportsclub_schema']);
				break;
			case 'ExerciseGym':
				$schema_full .= get_schema_exercisegym($schema['exercisegym_schema']);
				break;
			case 'TennisComplex':
				$schema_full .= get_schema_tenniscomplex($schema['tenniscomplex_schema']);
				break;
		}
		$schema_full .= '</script>';
	}
	echo $schema_full;
}


/* === Common Output Functions === */
// === Image
function output_image($image) {
	$schema  = '';
	$schema .= ','.PHP_EOL;
	$schema .= '  "image": {'.PHP_EOL;
	$schema .= '    "@context":"http://schema.org",'.PHP_EOL;
	$schema .= '    "@type":"ImageObject",'.PHP_EOL;
	$schema .= '    "url":"'.$image['url'].'",'.PHP_EOL;
	$schema .= '    "description":"'.$image['description'].'"'.PHP_EOL;
	$schema .= '  }';
	return $schema;
}
// === Address
function output_address($address) {
	$schema  = '';
	$schema .= ','.PHP_EOL;
	$schema .= '  "address": {'.PHP_EOL;
	$schema .= '    "@type": "PostalAddress",'.PHP_EOL;
	$schema .= '    "addressCountry": "'.$address['country'].'",'.PHP_EOL;
	$schema .= '    "addressLocality": "'.$address['city'].'",'.PHP_EOL;
	$schema .= '    "addressRegion": "'.$address['state'].'",'.PHP_EOL;
	$schema .= '    "postalCode": "'.$address['postal_code'].'",'.PHP_EOL;
	$schema .= '    "streetAddress": "'.$address['street_address'].'"'.PHP_EOL;
	$schema .= '  }';
	return $schema;
}
// === Contact Point
function output_contact_point($contact_point) {
	$schema  = '';
	$schema .= ','.PHP_EOL;
	$schema .= '  "contactPoint": ['.PHP_EOL;
	$num_items = count($contact_point);
  $loop_count = 0;
  foreach($contact_point as $k => $value) {
    $schema .= '    {'.PHP_EOL;
    $schema .= '      "@type":"ContactPoint",'.PHP_EOL;
    $schema .= '      "telephone":"'.$value['telephone'].'",'.PHP_EOL;
    $schema .= '      "email":"'.$value['email'] . '",'.PHP_EOL;
    $schema .= '      "contactType":"'.$value['contact_type'].'"'.PHP_EOL;
  	$schema .= '    }';
  	$loop_count++;
  	if ($loop_count<$num_items) { $schema .= ','.PHP_EOL; } else { $schema .= PHP_EOL; }
  }
	$schema .= '  ]';
	return $schema;
}

// === Offers
function output_offers($arr, $name) {
	$schema  = '';
	$schema .= ','.PHP_EOL;
	$schema .= '  "'.$name.'": ['.PHP_EOL;
	$num_items = count($arr);
  $loop_count = 0;
  foreach($arr as $k => $value) {
    $schema .= '    {'.PHP_EOL;
    $schema .= '      "@type":"Offer",'.PHP_EOL;
    $schema .= '      "name":"'.$value['name'].'",'.PHP_EOL;
    $schema .= '      "description":"'.$value['description'].'",'.PHP_EOL;
    $schema .= '      "url":"'.$value['url'].'",'.PHP_EOL;
    $schema .= '      "priceCurrency":"'.$value['priceCurrency'].'",'.PHP_EOL;
    $schema .= '      "price":"'.$value['price'].'",'.PHP_EOL;
    $schema .= '      "availability":"https://schema.org/'.$value['availability'].'"'.PHP_EOL;
  	$schema .= '    }';
  	$loop_count++;
  	if ($loop_count<$num_items) { $schema .= ','.PHP_EOL; } else { $schema .= PHP_EOL; }
  }
	$schema .= '  ]';
	return $schema;
}

// === Same As
function output_same_as($same_as) {
	$schema  = '';
	$schema .= ','.PHP_EOL;
	$schema .= '  "sameAs": ['.PHP_EOL;
	$num_items = count($same_as);
	$loop_count = 0;
	foreach($same_as as $k => $value) {
		$schema .= '    "'.$value['url'].'"';
		$loop_count++;
		if ($loop_count<$num_items) { $schema .= ','.PHP_EOL; } else { $schema .= PHP_EOL; }
	}
	$schema .= '  ]';
	return $schema;
}

// === Amenities
function output_amenities($arr) {
	$schema  = '';
	$schema .= ','.PHP_EOL;
	$schema .= '  "amenityFeature": ['.PHP_EOL;
	$num_items = count($arr);
	$loop_count = 0;
	foreach($arr as $k => $value) {
		$schema .= '	{"@type": "LocationFeatureSpecification", "name": "'.$value['amenity'].'", "value": "true"}';
		$loop_count++;
		if ($loop_count<$num_items) { $schema .= ','.PHP_EOL; } else { $schema .= PHP_EOL; }
	}
	$schema .= '  ]';
	return $schema;
}

// === Opening Hours
function output_opening_hours($opening_hours) {
	$schema  = '';
	$schema .= ','.PHP_EOL;
	$schema .= '  "openingHoursSpecification": ['.PHP_EOL;
	$num_items = count($opening_hours);
	$loop_count = 0;
	foreach($opening_hours as $k => $value) {

	  $schema .= '    {'.PHP_EOL;
	  $schema .= '      "@type":"OpeningHoursSpecification",'.PHP_EOL;
	  $schema .= '      "opens":"'.$value['opens'].'",'.PHP_EOL;
	  $schema .= '      "dayOfWeek":"https://schema.org/'.ucfirst($value['day']) . '",'.PHP_EOL;
	  $schema .= '      "closes":"'.$value['closes'].'"'.PHP_EOL;
		$schema .= '    }';

		$loop_count++;
		if ($loop_count<$num_items) { $schema .= ','.PHP_EOL; } else { $schema .= PHP_EOL; }
	}
	$schema .= '  ]';
	return $schema;
}

// === FAQs
function output_faqs($arr) {
	$schema  = '';
	$schema .= '  "mainEntity": ['.PHP_EOL;
	$num_items = count($arr);
	$loop_count = 0;
	foreach($arr as $k => $value) {

	  $schema .= '    {'.PHP_EOL;
	  $schema .= '      "@type":"Question",'.PHP_EOL;
	  $schema .= '      "name":"'.$value['question'].'",'.PHP_EOL;
		$schema .= '  		"acceptedAnswer": {'.PHP_EOL;
		$schema .= '  		  "@type": "Answer",'.PHP_EOL;
		$schema .= '  		  "text": "'.$value['answer'].'"'.PHP_EOL;
		$schema .= '  		}'.PHP_EOL;
		$schema .= '    }';

		$loop_count++;
		if ($loop_count<$num_items) { $schema .= ','.PHP_EOL; } else { $schema .= PHP_EOL; }
	}
	$schema .= '  ]';
	return $schema;
}

// === Offers
function output_offer($offer) {
	$schema  = '';
	$schema .= ','.PHP_EOL;
	$schema .= '  "offers": ['.PHP_EOL;
	$num_items = count($offer);
  $loop_count = 0;
  foreach($offer as $k => $value) {
    $schema .= '    {'.PHP_EOL;
    $schema .= '      "@type":"Offer",'.PHP_EOL;
    $schema .= '      "name":"'.$value['name'].'",'.PHP_EOL;
    $schema .= '      "description":"'.$value['description'].'",'.PHP_EOL;
    if (isset($value['url']) && !empty($value['url'])) {
  		$schema .= '      "url":"'.$value['url'].'"';
  		if (isset($value['image']) && !empty($value['image'])) {
  			$schema .= ','.PHP_EOL;
			}else{
				$schema .= PHP_EOL;
  		}
    }
    if (isset($value['image']) && !empty($value['image'])) {
  		$schema .= '      "image":"'.$value['image'].'"'.PHP_EOL;
    }
  	$schema .= '    }';
  	$loop_count++;
  	if ($loop_count<$num_items) { $schema .= ','.PHP_EOL; } else { $schema .= PHP_EOL; }
  }
	$schema .= '  ]';
	return $schema;
}

// === Geo
function output_geo($geo) {
	$schema  = '';
	$schema .= ','.PHP_EOL;
	$schema .= '  "geo": {'.PHP_EOL;
	$schema .= '    "@type": "GeoCoordinates",'.PHP_EOL;
	$schema .= '    "latitude": "'.$geo['latitude'].'",'.PHP_EOL;
	$schema .= '    "longitude": "'.$geo['longitude'].'"'.PHP_EOL;
	$schema .= '  }';
	return $schema;
}

// === Room Details (from main array)
function output_room_details($arr){

	$schema  = '';
	// $schema .= ','.PHP_EOL;
	
		if (!empty($arr['alternate_name'])) {
			$schema .= ','.PHP_EOL;
			$schema .= '  "alternateName": "'.$arr['alternate_name'].'"';
		}
		if (!empty($arr['bed'])) {
			$schema .= ','.PHP_EOL;
			$schema .= '  "bed": "'.$arr['bed'].'"';
		}
		if (!empty($arr['occupancy'])) {
			$schema .= ','.PHP_EOL;
			$schema .= '  "occupancy": "'.$arr['occupancy'].'"';
		}
		if (!empty($arr['floor_size'])) {
			$schema .= ','.PHP_EOL;
			$schema .= '  "floorSize": "'.$arr['floor_size'].'"';
		}
		if (!empty($arr['floor_level'])) {
			$schema .= ','.PHP_EOL;
			$schema .= '  "floorLevel": "'.$arr['floor_level'].'"';
		}
		if (!empty($arr['number_of_bathrooms'])) {
			$schema .= ','.PHP_EOL;
			$schema .= '  "numberOfBathroomsTotal": "'.$arr['number_of_bathrooms'].'"';
		}
		if (!empty($arr['number_of_bedrooms'])) {
			$schema .= ','.PHP_EOL;
			$schema .= '  "numberOfBedrooms": "'.$arr['number_of_bedrooms'].'"';
		}
		if (!empty($arr['number_of_rooms'])) {
			$schema .= ','.PHP_EOL;
			$schema .= '  "numberOfRooms": "'.$arr['number_of_rooms'].'"';
		}
		if (!empty($arr['pets_allowed'])) {
			$schema .= ','.PHP_EOL;
			$schema .= '  "petsAllowed": "true"';
		}
		if (!empty($arr['smoking_allowed'])) {
			$schema .= ','.PHP_EOL;
			$schema .= '  "smokingAllowed": "true"';
		}


	return $schema;

}


/* === Structured Data Functions === */
/* === Resort === */
function get_schema_resort($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "Resort",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
    $schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-Resort",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'"';
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['contact_point'])) { $schema .= output_contact_point($arr['contact_point']); }
	if (!empty($arr['same_as'])) { $schema .= output_same_as($arr['same_as']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Hotel === */
function get_schema_hotel($arr) {
    $schema  = ''.PHP_EOL;
    $schema .= '{'.PHP_EOL;
    $schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
    $schema .= '  "@type": "Hotel",'.PHP_EOL;
    $schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
    $schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
    $schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
    $schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-Hotel",'.PHP_EOL;
    $schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
    $schema .= '  "priceRange": "'.$arr['price_range'].'"';
    if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
    if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
    if (!empty($arr['contact_point'])) { $schema .= output_contact_point($arr['contact_point']); }
    if (!empty($arr['same_as'])) { $schema .= output_same_as($arr['same_as']); }
    $schema .= PHP_EOL.'}'.PHP_EOL;
    return $schema;
}

/* === Organization === */
function get_schema_organization($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "Organization",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
    $schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-Organization",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'"';
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['contact_point'])) { $schema .= output_contact_point($arr['contact_point']); }
	if (!empty($arr['same_as'])) { $schema .= output_same_as($arr['same_as']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Program Membership === */
function get_schema_program_membership($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "ProgramMembership",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "programName": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-Organization",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "hostingOrganization": {'.PHP_EOL;
	$schema .= '    "@type": "Organization",'.PHP_EOL;
	$schema .= '    "name": "'.$arr['hosting_organization'].'"'.PHP_EOL;
	$schema .= '  }';
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['same_as'])) { $schema .= output_same_as($arr['same_as']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Offer === */
function get_schema_offer($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "AggregateOffer",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'"'.PHP_EOL;
	// offers repeater
	if (!empty($arr['offers'])) { $schema .= output_offer($arr['offers']); }
	if (!empty($arr['same_as'])) { $schema .= output_same_as($arr['same_as']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Offer === */
function get_schema_faq($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "FAQPage",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "author": "'.$arr['author'].'",'.PHP_EOL;
	// offers repeater
	if (!empty($arr['faqs'])) { $schema .= output_faqs($arr['faqs']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Contact Page === */
function get_schema_contact($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "ContactPage",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "potentialAction": "Contact"'.PHP_EOL;
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}


/* === Park === */
function get_schema_park($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "Park",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-Park",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'"';
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Hotel Room === */
function get_schema_hotelroom($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "HotelRoom",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-HotelRoom",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "keywords": "'.$arr['keywords'].'",'.PHP_EOL;
	$schema .= '  "tourBookingPage": "'.$arr['tour_booking_page'].'"';
	$schema .= output_room_details($arr);
	if (!empty($arr['amenities'])) { $schema .= output_amenities($arr['amenities']); }
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Restaurant === */
function get_schema_restaurant($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "Restaurant",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-Restaurant",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "servesCuisine": "'.$arr['serves_cuisine__type_of_cuisine'].'",'.PHP_EOL;
	$schema .= '  "hasMenu": "'.$arr['menu_link'].'",'.PHP_EOL;
	$arr['accepts_reservations'] = $arr['accepts_reservations'] ? 'True' : 'False';
	$schema .= '  "acceptsReservations": "'.$arr['accepts_reservations'].'"';
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['contact_point'])) { $schema .= output_contact_point($arr['contact_point']); }
	if (!empty($arr['same_as'])) { $schema .= output_same_as($arr['same_as']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Bar or Pub === */
function get_schema_barorpub($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "BarOrPub",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-BarOrPub",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'",'.PHP_EOL;
	$schema .= '  "hasMenu": "'.$arr['menu_link'].'",'.PHP_EOL;
	$arr['accepts_reservations'] = $arr['accepts_reservations'] ? 'True' : 'False';
	$schema .= '  "acceptsReservations": "'.$arr['accepts_reservations'].'"';
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['contact_point'])) { $schema .= output_contact_point($arr['contact_point']); }
	if (!empty($arr['same_as'])) { $schema .= output_same_as($arr['same_as']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Cafe or Coffee Shop === */
function get_schema_cafeorcoffeeshop($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "CafeOrCoffeeShop",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-CafeOrCoffeeShop",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'",'.PHP_EOL;
	$schema .= '  "hasMenu": "'.$arr['menu_link'].'",'.PHP_EOL;
	$arr['accepts_reservations'] = $arr['accepts_reservations'] ? 'True' : 'False';
	$schema .= '  "acceptsReservations": "'.$arr['accepts_reservations'].'"';
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['contact_point'])) { $schema .= output_contact_point($arr['contact_point']); }
	if (!empty($arr['same_as'])) { $schema .= output_same_as($arr['same_as']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Event Venue === */
function get_schema_eventvenue($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "EventVenue",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-EventVenue",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "tourBookingPage": "'.$arr['tour_booking_page'].'",'.PHP_EOL;
	$schema .= '  "keywords": "'.$arr['keywords'].'",'.PHP_EOL;
	if (!empty($arr['maximum_attendee_capacity'])) { 
		$schema .= '  "maximumAttendeeCapacity": "'.$arr['maximum_attendee_capacity'].'"';
	}
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Event Venue === */
function get_schema_shoppingcenter($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "ShoppingCenter",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-ShoppingCenter",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "keywords": "'.$arr['keywords'].'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'"';
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Local Business === */
function get_schema_localbusiness($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "LocalBusiness",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-LocalBusiness",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'"';
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['contact_point'])) { $schema .= output_contact_point($arr['contact_point']); }
	if (!empty($arr['same_as'])) { $schema .= output_same_as($arr['same_as']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Golf Course === */
function get_schema_golfcourse($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "GolfCourse",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-GolfCourse",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "keywords": "'.$arr['keywords'].'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'"';
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}


/* === Day Spa === */
function get_schema_dayspa($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "DaySpa",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-DaySpa",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['priceRange'].'",'.PHP_EOL;
	if (!empty($arr['has_offer_catalog'])) { 
		$schema .= '  "hasOfferCatalog": "'.$arr['has_offer_catalog'].'"';
	}
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['same_as'])) { $schema .= output_same_as($arr['same_as']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Meeting Room === */
function get_schema_meetingroom($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "MeetingRoom",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-MeetingRoom",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['priceRange'].'",'.PHP_EOL;
	$schema .= '  "keywords": "'.$arr['keywords'].'",'.PHP_EOL;
	$schema .= '  "tourBookingPage": "'.$arr['tour_booking_page'].'",'.PHP_EOL;
	if (!empty($arr['maximum_attendee_capacity'])) { 
		$schema .= '  "maximumAttendeeCapacity": "'.$arr['maximum_attendee_capacity'].'"';
	}
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Accommodation === */
function get_schema_accommodation($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "Accommodation",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-Accommodation",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "keywords": "'.$arr['keywords'].'",'.PHP_EOL;
	$schema .= '  "tourBookingPage": "'.$arr['tour_booking_page'].'"';
	$schema .= output_room_details($arr);
	if (!empty($arr['amenities'])) { $schema .= output_amenities($arr['amenities']); }
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === SportsClub === */
function get_schema_sportsclub($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "SportsClub",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-SportsClub",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "keywords": "'.$arr['keywords'].'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'"';
	if (!empty($arr['amenities'])) { $schema .= output_amenities($arr['amenities']); }
	if (!empty($arr['offers'])) { $schema .= output_offers($arr['offers'], 'makesOffer'); }
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Exercise Gym === */
function get_schema_exercisegym($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "ExerciseGym",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-ExerciseGym",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "keywords": "'.$arr['keywords'].'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'"';
	if (!empty($arr['amenities'])) { $schema .= output_amenities($arr['amenities']); }
	if (!empty($arr['offers'])) { $schema .= output_offers($arr['offers'], 'makesOffer'); }
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}
/* === Tennis Complex === */
function get_schema_tenniscomplex($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "TennisComplex",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-TennisComplex",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "keywords": "'.$arr['keywords'].'",'.PHP_EOL;
	$schema .= '  "priceRange": "'.$arr['price_range'].'",'.PHP_EOL;
	if (!empty($arr['email'])) { $schema .= '  "email": "'.$arr['email'].'"'; }
	if (!empty($arr['amenities'])) { $schema .= output_amenities($arr['amenities']); }
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Suite === */
function get_schema_suite($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "Suite",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "telephone": "'.$arr['phone'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-Suite",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "keywords": "'.$arr['keywords'].'",'.PHP_EOL;
	$schema .= '  "tourBookingPage": "'.$arr['tour_booking_page'].'"';
	$schema .= output_room_details($arr);
	if (!empty($arr['amenities'])) { $schema .= output_amenities($arr['amenities']); }
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	if (!empty($arr['geo'])) { $schema .= output_geo($arr['geo']); }
	if (!empty($arr['opening_hours'])) { $schema .= output_opening_hours($arr['opening_hours']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Event === */
function get_schema_event($arr) {
	$start_time = $arr['start_time'];
	$end_time = $arr['end_time'];
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "Event",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-Event",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	$schema .= '  "startDate": "'.$arr['start_date'].$start_time.'",'.PHP_EOL;
	$schema .= '  "endDate": "'.$arr['end_date'].$end_time.'",'.PHP_EOL;
	$schema .= '  "location": {'.PHP_EOL;
	$schema .= '      "@type": "Place",'.PHP_EOL;
	$schema .= '      "name": "'.$arr['location']['name'].'",'.PHP_EOL;
	$schema .= '      "address": {'.PHP_EOL;
	$schema .= '          "@type": "PostalAddress",'.PHP_EOL;
	$schema .= '          "addressCountry": "'.$arr['location']['country'].'",'.PHP_EOL;
	$schema .= '          "addressLocality": "'.$arr['location']['city'].'",'.PHP_EOL;
	$schema .= '          "addressRegion": "'.$arr['location']['state'].'",'.PHP_EOL;
	$schema .= '          "postalCode": "'.$arr['location']['postal_code'].'",'.PHP_EOL;
	$schema .= '          "streetAddress": "'.$arr['location']['street_address'].'"'.PHP_EOL;
	$schema .= '      }'.PHP_EOL;
	$schema .= '  },'.PHP_EOL;
	if (!empty($arr['performers'])) { $schema .= '  "performers": "'.$arr['performers'].'"'; }
	if (!empty($arr['offers'])) { $schema .= output_offers($arr['offers'], 'offers'); }
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}

/* === Product === */
function get_schema_product($arr) {
	$schema  = ''.PHP_EOL;
	$schema .= '{'.PHP_EOL;
	$schema .= '  "@context": "http://schema.org/",'.PHP_EOL;
	$schema .= '  "@type": "Product",'.PHP_EOL;
	$schema .= '  "name": "'.$arr['name'].'",'.PHP_EOL;
	$schema .= '  "url": "'.get_the_permalink().'",'.PHP_EOL;
	$schema .= '  "mainEntityOfPage": "'.get_the_permalink().'?utm_source=schema&utm_medium=organic&utm_campaign=ldjson-Product",'.PHP_EOL;
	$schema .= '  "description": "'.addslashes($arr['description']).'",'.PHP_EOL;
	if (!empty($arr['brand'])) { 
	$schema .= '  "brand": {
    "@type": "Brand",
    "name": "'.$arr['brand'].'"
  }';
	}
	if (!empty($arr['offers'])) { $schema .= output_offers($arr['offers'], 'offers'); }
	if (!empty($arr['image'])) { $schema .= output_image($arr['image']); }
	if (!empty($arr['address'])) { $schema .= output_address($arr['address']); }
	$schema .= PHP_EOL.'}'.PHP_EOL;
	return $schema;
}