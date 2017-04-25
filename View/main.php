<!DOCTYPE html>
<!--

-->
<html>
    <head>
        <!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABN-TOaYx18VgqXLxlJq87j3ZDtREJKdk"></script>!-->
        <script type="text/javascript" src="../js/jquery-1.12.3.js"></script>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/maincss.css">
        <title>Congressional Map</title>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#state-menu").change(function() {
                    stateChanged($(this).val());
                });
                
                $("#district-menu").change(postRepInfo, function() {
                    postRepInfo($("#district-menu"), $("#rep-menu"));
                });
                $("#rep-menu").change(postRepInfo, function() {
                    postRepInfo($("#rep-menu"), $("#district-menu"));
                });
                
                $("#address").keydown(function(key) {
                    if (key.keyCode === 13)
                    {
                        key.preventDefault();
                        displayAddress($("#address").val());
                    }
                });
                
                $("#find-address").click(function() {
                    displayAddress($("#address").val());
                });
            });
            
            function stateChanged(state, callback) {
                if (state === "") {
                    $("#rep-menu").empty().append("<option>None</option>");
                    $("#district-menu").empty().append("<option>None</option>");
                    emptyData();
                    emptyMap();
                } else {
                    emptyData();
                    emptyMap();
                    $.when(
                        $.post("../Model/districtnumbers.php", {"state":state}, populateDistrictNum),
                        $.post("../Model/repnames.php", {"state":state}, populateRepName))
                    .done(callback);
                }
            }
            
            function postRepInfo(selector, otherSelector) {
                var value = selector.val();
                otherSelector.val(value);
                if (value !== "") {
                    var arr = value.split(' ');
                    $.post("../Model/repinfo.php", {"state":arr[0], "districtid":arr[1]}, displayData);
                } else {
                    emptyMap();
                }
            }
            
            function populateDistrictNum(theJsonDN) {
                var data = JSON.parse(theJsonDN);
                var selectDN = $("#district-menu").empty();
                if (data.DistrictIds.length > 0) {
                    selectDN.append($('<option>', {
                        text: "Select " + data.DistrictIds[0].state + " District",
                        value: ""
                    }));
                    $.each(data.DistrictIds, function(i, info) {
                        if (info.district === "00") {
                            selectDN.append($('<option>', {
                                text: "At-Large",
                                value: info.state + " " + info.district
                            }));
                        }
                        else {
                            selectDN.append($('<option>', {
                              text: info.district,
                                value: info.state + " " + info.district
                            }));
                        }
                    });
                } else {
                    selectDN.append("<option>None</option>");
                }
            }
            
            function populateRepName(theJsonRN) {
                var data = JSON.parse(theJsonRN);
                var selectRN = $("#rep-menu").empty();
                if (data.Representatives.length > 0) {
                    selectRN.append($('<option>', {
                        text: "Select " + data.Representatives[0].state + " Representative",
                        value: ""
                    }));
                    $.each(data.Representatives, function(i, info) {
                        selectRN.append($('<option>', {
                            text: info.lastName + ", " + info.firstName,
                            value: info.state + " " + info.district
                        }));
                    });
                } else {
                    selectRN.append("<option>None</option>");
                }
            }
            
            function displayData(theJsonInfo)
            {
                var data = JSON.parse(theJsonInfo);
                if (data.RepInfo.length > 0)
                {
                    for (var i = 0; i < data.RepInfo.length; i++)
                    {
                        document.getElementById("table"+i).rows[0].cells.item(1).innerHTML = data.RepInfo[i].firstName + " " + data.RepInfo[i].lastName;
                        document.getElementById("table"+i).rows[1].cells.item(1).innerHTML = data.RepInfo[i].party;
                        document.getElementById("table"+i).rows[2].cells.item(1).innerHTML = data.RepInfo[i].officeAdd;
                        document.getElementById("table"+i).rows[3].cells.item(1).innerHTML = data.RepInfo[i].dcPhone;
                        document.getElementById("table"+i).rows[4].cells.item(1).innerHTML = data.RepInfo[i].districtPhone;
                        document.getElementById("emaila"+i).href = data.RepInfo[i].electContact;
                        document.getElementById("emaila"+i).text = data.RepInfo[i].electContact;         
                        document.getElementById("emailb"+i).href = data.RepInfo[i].webAdd;
                        document.getElementById("emailb"+i).text = data.RepInfo[i].webAdd; 
                    }
                    $("infoDisplay").show();
                    var party = data.RepInfo[0].party;
                    var district = data.RepInfo[0].district;
                    var state = data.RepInfo[0].state;
                    displayDistrict(party, state, district);
                } else {
                    emptyData();
                }  
            }
            
            function emptyData() {
                $("infoDisplay").hide();
                for (var i = 0; i < 3; i++) {
                    document.getElementById("table"+i).rows[0].cells.item(1).innerHTML = "";
                    document.getElementById("table"+i).rows[1].cells.item(1).innerHTML = "";
                    document.getElementById("table"+i).rows[2].cells.item(1).innerHTML = "";
                    document.getElementById("table"+i).rows[3].cells.item(1).innerHTML = "";
                    document.getElementById("table"+i).rows[4].cells.item(1).innerHTML = "";
                    document.getElementById("emaila"+i).href = "";
                    document.getElementById("emaila"+i).text = "";
                    document.getElementById("emailb"+i).href = "";
                    document.getElementById("emailb"+i).text = ""; 
                }
            }
            
            function displayDistrict(party, state, district) {
                $.post("../Model/districtInfo.php", {"state":state, "districtid":district}, function(mapJSON) {
                    emptyMap();
                    if (party === "Republican") {
                        map.data.setStyle({ 
                            fillColor: 'red', 
                            strokeWeight: 1 
                        }); 
                    } else if (party === "Democrat") {
                        map.data.setStyle({ 
                            fillColor: 'blue', 
                            strokeWeight: 1 
                        }); 
                    } else if (party === "Independent") {
                        map.data.setStyle({ 
                            fillColor: 'green', 
                            strokeWeight: 1 
                        }); 
                    }
                    var mapData = JSON.parse(mapJSON);
                    map.data.addGeoJson(mapData);
		});
            }
            
        </script>
    </head>
    <body>
        <div id="wrapper">
            <?php include 'header.html';?>
            <div id="main_instructions">
                <p><b>Welcome to the Summer 2016 Congressional District Map website. To begin, choose an option:</b></p>  
                <div id="sub_main">
                <p>Option 1: From the drop-down menus, select a state, then either a representative or a district.</p> 
                <p>Option 2: Enter an address in the field below. How to format the address can be found 
		<a class="other_footer_link" href="address.php">here</a>.</p> 
                <p>Option 3: Click anywhere in the U.S. in the Google Map.</p> 
                <p><i>Note: Look below the map to see representative and senator information.</i></p> 
                </div>
            </div>

            <div id="menus">
                <div class="menu">
                    <p class="inline"><b>Select State: </b></p>
                    <select id="state-menu" onchange='this.size=0;' onblur="this.size=0;">
                        <option value="">Select a State</option>
                        <option value="AL">Alabama</option>
                        <option value="AK">Alaska</option>
                        <option value="AR">Arkansas</option>
                        <option value="AZ">Arizona</option>
                        <option value="CA">California</option>
                        <option value="CO">Colorado</option>
                        <option value="CT">Connecticut</option>
                        <option value="DE">Delaware</option>
                        <option value="FL">Florida</option>
                        <option value="GA">Georgia</option>
                        <option value="HI">Hawaii</option>
                        <option value="ID">Idaho</option>
                        <option value="IL">Illinois</option>
                        <option value="IN">Indiana</option>
                        <option value="IA">Iowa</option>
                        <option value="KS">Kansas</option>
                        <option value="KY">Kentucky</option>
                        <option value="LA">Louisiana</option>
                        <option value="ME">Maine</option>
                        <option value="MA">Massachusetts</option>
                        <option value="MD">Maryland</option>
                        <option value="MI">Michigan</option>
                        <option value="MN">Minnesota</option>
                        <option value="MS">Mississippi</option>
                        <option value="MO">Missouri</option>
                        <option value="MT">Montana</option>
                        <option value="NE">Nebraska</option>
                        <option value="NV">Nevada</option>
                        <option value="NH">New Hampshire</option>
                        <option value="NJ">New Jersey</option>
                        <option value="NM">New Mexico</option>
                        <option value="NY">New York</option>
                        <option value="NC">North Carolina</option>
                        <option value="ND">North Dakota</option>
                        <option value="OH">Ohio</option>
                        <option value="OK">Oklahoma</option>
                        <option value="OR">Oregon</option>
                        <option value="PA">Pennsylvania</option>
                        <option value="RI">Rhode Island</option>
                        <option value="SC">South Carolina</option>
                        <option value="SD">South Dakota</option>
                        <option value="TN">Tennessee</option>
                        <option value="TX">Texas</option>
                        <option value="UT">Utah</option>
                        <option value="VT">Vermont</option>
                        <option value="VA">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="WI">Wisconsin</option>
                        <option value="WV">West Virginia</option>
                        <option value="WY">Wyoming</option>
                    </select>
                </div>
                <div class="menu">
                    <p class="inline"><b>Select Representative: </b></p>
                    <select id="rep-menu" >
                        <option>None</option>
                    </select>
                </div>

                <div class="menu">
                    <p class="inline"><b>Select District: </b></p>
                    <select id="district-menu">
                        <option>None</option>
                    </select>
                </div>
            </div>
            <form id="myForm">
            <div id="address-wrapper">
                <p class="inline"><b>Enter Address: </b></p>
                <input id="address" type="text">
                <button id="find-address" type="button">Find</button>
                <button id="find-address" type="reset" value="Reset">Clear</button>
            </div>
            </form>

        </div>
        <div id="map_wrapper">
            <div id="map">
                <script>
                    var map;
                    var geocoder;
                    function initMap() {
                        map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 4,
                            center: {lat: 39.8239544, lng: -95.229650}
                        });
                    
                        map.data.addListener('addfeature', function(event) {
                            var bounds = new google.maps.LatLngBounds();
                            event.feature.getGeometry().forEachLatLng(function(latLng) {
                               bounds.extend(latLng);
                            });
                            map.fitBounds(bounds);
                            map.setCenter(bounds.getCenter());
                        });
                        
                        google.maps.event.addListener(map, "click", function(event) {
                            var obj = event.latLng;
                            displayCoordinate(obj);
                        });
                        
                        geocoder = new google.maps.Geocoder();
                    }
                
                    function emptyMap() {
                        map.data.forEach(function(feature) {
                            map.data.remove(feature); 
                        });
                        /*map.setOptions({
                            zoom: 4,
                            center: {lat: 39.8239544, lng: -95.229650}
                        });*/
                    }
                    
                    function displayAddress(address) {
                        geocoder.geocode({"address" : address}, function(result, status) {
                            if (typeof result[0] === "undefined")
                            {
                                alert("Address not Found! Please try again.")
                            }
                            else if (status === google.maps.GeocoderStatus.OK) {
                                displayCoordinate(result[0].geometry.location);
                            }
                        });
                    }
                    
                    function displayCoordinate(latLng) {
                        findGeoid(latLng, function(geoid) {
                            $.post("../Model/infoFromGeoid.php", {"geoid": geoid}, function(info) {
                                var data = JSON.parse(info);
                                $("#state-menu").val(data.state);
                                stateChanged(data.state, function() {
                                    $("#district-menu").val(data.state + " " + data.district).change();
                                });
                            });
                        });
                    }
                    
                    function findGeoid(latLng, geoidCallback) {
                        $.getJSON("cd_114.json", function(json) {
                            $.each(json.features, function(i, district) {
                                if (district.geometry.type === "Polygon") {
                                    $.each(district.geometry.coordinates, function(j, coordinates) {
                                        if (contains(coordinates, latLng)) {
                                            geoidCallback(district.properties.GEOID);
                                        }
                                    });
                                } else if (district.geometry.type === "GeometryCollection") {
                                    $.each(district.geometry.geometries, function(j, geometry) {
                                        $.each(geometry.coordinates, function(k, coordinates) {
                                            if (contains(coordinates, latLng)) {
                                                geoidCallback(district.properties.GEOID);
                                            }
                                        });
                                    });
                                }
                            });
                        });
                    }
                    
                    function contains(coordinates, latLng) {
                        var formattedCoords = new Array(coordinates.length);
                        $.each(coordinates, function(i, coordinate) {
                            formattedCoords[i] = new google.maps.LatLng(coordinate[1], coordinate[0]);
                        });
                        
                        var poly = new google.maps.Polygon({
                            paths: formattedCoords
                        });
                        return google.maps.geometry.poly.containsLocation(latLng, poly);
                    }
                </script>
                <script 
                    src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyA4UBgtkXPMO3SLhUKdFdosREmhRDbFG_s&callback=initMap">
                </script>
            </div>
        </div>
        <br>
        <div id="infoDisplay">
            <table id = "table0">
                <caption><b>Representative Information</b></caption>
                <tr>
                   <td>Name: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>Party: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>DC Address: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>DC Phone: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>District Phone: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>Contact: </td> 
                   <td class="info_col"><a id = "emaila0" class="table_info"></a></td>
                </tr>
                <tr>
                   <td>Official Web-site: </td> 
                   <td class="info_col"><a id = "emailb0" class="table_info"></a></td>
                </tr>
	    </table>
            <br>
            <table id = "table1">
                <caption><b>Senator #1 Information</b></caption> 
                <tr>
                   <td>Name: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>Party: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>DC Address: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>DC Phone: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>District Phone: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>Contact: </td> 
                   <td class="info_col"><a id = "emaila1" class="table_info"></a></td>
                </tr>
                <tr>
                   <td>Official Web-site: </td> 
                   <td class="info_col"><a id = "emailb1" class="table_info"></a></td>
                </tr>
	    </table>
            <br>
            <table id = "table2">
                <caption><b>Senator #2 Information</b></caption> 
                <tr>
                   <td>Name: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>Party: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>DC Address: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>DC Phone: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>District Phone: </td> 
                   <td class="info_col"></td>
                </tr>
                <tr>
                   <td>Contact: </td> 
                   <td class="info_col"><a id = "emaila2" class="table_info"></a></td>
                </tr>
                <tr>
                   <td>Official Web-site: </td> 
                   <td class="info_col"><a id = "emailb2" class="table_info"></a></td>
                </tr>
		</table>  
            
        </div>
        
        <div id="footer">
            <div id="footer_description">
            <p><i>Disclaimer: The above map is for approximation purposes only.  To find the exact
		congressional district for an address, use 
		<a class="other_footer_link" href="http://www.house.gov/representatives/find/">this</a> government website.</i></p>
            </div>
        </div>

    </body>
</html>
