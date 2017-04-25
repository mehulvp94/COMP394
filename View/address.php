<!DOCTYPE html>
<html> 
    <head> 
        <meta charset="UTF-8"> 
        <link rel="stylesheet" href="css/maincss.css"> 
        <title>Congressional Map - Address Format</title> 
    </head> 
    <body> 
            <?php include 'header.html';?> 
            <div id="content"> 
                <h2>Address Format</h2>
                <p id='desc'>The following abridged list is taken from the Google Maps API website* and provides  
                some guidelines on how to best format an address.</p> 
                <br> 
                <div id="address_format"> 
			<ul>
				<li>Specify addresses in accordance with the format used by the postal service.</li>
				<li>Do not specify additional address elements such as business names, unit numbers, floor numbers, or suite numbers.</li>
				<li>Use the street number of a premise in preference to the building name where possible.</li>
				<li>Use street number addressing in preference to specifying cross streets where possible. </li>
				<li>County Roads: "Co Road NNN" where NNN is the road number.</li>
				<li>State Highways: "State NNN" where State is the full name of the state and NNN is the highway number.</li>
				<li>U.S. Highways: "U.S. NNN" where NNN is the highway number.</li>
				<li>U.S. Interstates: "Interstate NNN" where NNN is the interstate number.</li>
			</ul>
                </div> 
		<br>
		<br>
		<p><i>*Source: <a href="https://developers.google.com/maps/faq#geocoder_queryformat">Google Maps API</a></i></p>
            </div> 
        <div id="other_footer"></div> 
    </body> 
</html> 
