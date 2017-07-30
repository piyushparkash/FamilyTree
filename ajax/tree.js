/* global user_id */

var labelType, useGradients, nativeTextSupport, animate, selected_member, tree;

(function () {
    var ua = navigator.userAgent,
        iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
        typeOfCanvas = typeof HTMLCanvasElement,
        nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
        textSupport = nativeCanvasSupport &&
            (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
    //I'm setting this based on the fact that ExCanvas provides text support for IE
    //and that as of today iPhone/iPad current text support is lame
    labelType = (!nativeCanvasSupport || (textSupport && !iStuff)) ? 'Native' : 'HTML';
    nativeTextSupport = labelType == 'Native';
    useGradients = nativeCanvasSupport;
    animate = !(iStuff || !nativeCanvasSupport);
})();

/**
 * This function is used to select the current logged in user
 * in the Tree
 * @return null
 */
function showUser(id) {
    tree.select(id);
    //show the operations toolbar
    selected_member = id;
    tree.onClick(selected_member);
    var node = tree.graph.getNode(selected_member);
    //display data on right container
    display_data(node);
}

function loadFamily(familyid, onComplete) {
    var url = "createjson.php?familyid=" + familyid;
    $.getJSON(url, "", function (data) {
        //Clear the previous Tree
        tree.graph.empty();
        tree.labels.clearLabels(true);
        tree.canvas.clear();

        //Load the new Json
        tree.loadJSON(data);

        //compute node positions and layout
        tree.compute();
        tree.plot();

        onComplete();
    });
}

function viewfamily(e) {
    e.disabled = true;
    e.innerText = "Loading...";
    //Get the family id of the current selected member
    var selectedmember = tree.graph.getNode(selected_member);

    //Load the family of the given member
    loadFamily(selectedmember.data.family_id, function () {

        //tree.onClick(tree.root);
        tree.select(selected_member);

        $("#girlfamilybutton").removeAttr("disabled").text("View Family");
        $("#girlfamilybutton").fadeOut("medium");
    });

}
//Function to clear previously displayed data by display_data()
function display_clear_data() {
    $("#display_name").html("");
    $("#display_dob").html("");
    $("#display_relationship").html("");
    $("#display_alive").html("");
    $("#display_image").src = "";
}

function rootfamily() {
    rootnode = tree.graph.getNode(tree.root);
    var rootfamilyid = rootnode.data.familyid;
    return parseInt(rootfamilyid);
}

//Function to display data in the right container of the current selected_member
/**
 *
 * @param {type} node
 * @returns {undefined}
 */
function display_data(node) {
    //Remove all previous data
    display_clear_data();

    var displayDOB, displayRelationship, displayAlive, displayGaon;

    displayDOB = (node.data.dob.trim()) ? "Born on " + node.data.dob : "Birth Date not known";
    displayRelationship = node.data.gender == Vanshavali.MALE ? "He is " + node.data.relationship_status : "She is " + node.data.relationship_status;

    //Try Catch as, for most Village name is not mentioned
    try {
        displayGaon = (node.data.gaon.trim()) ? "Belongs to " + node.data.gaon : "Village not known";
    }
    catch (e) {
        displayGaon = 'Village not known';
    }

    displayAlive = node.data.alive_id == 0 ? (node.data.gender == 0 ? "He is not with us anymore" : "She is not with us anymore") :
        (node.data.gender == 0 ? "He is alive" : "She is alive");

    //display the data
    $("#display_name").html(node.name);
    $("#display_dob").html(displayDOB);
    $("#display_relationship").html(displayRelationship);
    $("#display_alive").html(displayAlive);
    $("#display_image")[0].src = "assets/user_images/" + node.data.image;
    $("#display_gaon").html(displayGaon);

    if (typeof user_id !== "undefined") {
        $.post('relationtest.php', {
            "from": user_id,
            "to": node.id,
            dataType: 'json'
        }, function (data) {

            try {
                data = JSON.parse(data);
            }
            catch (e) {
                $("#display_relation").html("Unable to Calculate Relation");
                return;
            }
            //Check for error
            if (parseInt(data.error) == 1) {
                $("#display_relation").html("Unable to Calculate Relation");
                return;
            }

            if (node.data.gender == Vanshavali.MALE) {
                $("#display_relation").html('He is your ' + data.relation);
            }
            else {
                $("#display_relation").html('She is your ' + data.relation);
            }
        });
    } else {
        $("#display_relation").html("Login to view relation");
    }

    //Now decide whether to show Girls Family Button or not

    if (rootfamily() != node.data.familyid) {
        $("#girlfamilybutton").fadeIn("medium")
    } else {
        $("#girlfamilybutton").fadeOut("medium")
    }

    var currMemberParents = node.getParents();

    if (currMemberParents.length > 0) {
        //Member already has a parents
        $("#parentOperation").hide();
    }
    else {
        $("#parentOperation").show();
    }

    //Check the relationship status of the member
    if (node.data.relationship_status_id == Vanshavali.MARRIED) {
        //Add spouse option should be not shown now
        $("#wifeoperation").hide();
        $("#husbandoperation").hide();
        return;
    }

    //Show option to add wife only if the member is not a girl
    if (parseInt(node.data.gender) == 0) {
        $("#wifeoperation").show();
        $("#husbandoperation").hide();
    } else {
        $("#wifeoperation").hide();
        $("#husbandoperation").show();
    }

}


function init() {
    var json;
    //init data
    $.getJSON("createjson.php", "", function (data) {
        json = data;
        //init Spacetree
        //Create a new ST instance
        var st = new $jit.ST({
            //id of viz container element
            injectInto: 'infovis',
            //set duration for the animation
            duration: 500,
            //set animation transition type
            transition: $jit.Trans.Expo.easeInOut,
            //set distance between node and its children
            levelDistance: 20,
            //Top to bottom orientation
            orientation: "top",
            //enable panning
            Navigation: {
                enable: true,
                panning: true,
                zooming: 50
            },
            //set node and edge styles
            //set overridable=true for styling individual
            //nodes or edges
            Node: {
                autoHeight: true,
                autoWidth: true,
                //height: 60,
                //width: 60,
                type: 'rectangle',
                align: "center",
                color: '#aaa',
                overridable: true
            },
            // NodeStyles: {
            //     enable: true,
            //     type: 'rectangle',
            //     stylesHover: {
            //         alpha: 0.5
            //     }
            // },
            Edge: {
                type: 'bezier',
                overridable: true
            },
            Label: {
                type: "HTML",
                overridable: true,
                style: 'bold',
                color: "#000"
            },
            //This method is called on DOM label creation.
            //Use this method to add event handlers and styles to
            //your node.
            onCreateLabel: function (label, node) {
                console.log("Creating Lable for node : " + node.name);
                label.id = node.id;
                label.innerHTML = node.name;
                label.onclick = function () {
                    //Check if the user has clicked twice
                    if (selected_member == node.id) {
                        console.log("Double Click");
                        //Now we have to show right container
                        $("#rightcontainerheader").show('slide', {
                            direction: 'right',
                            easing: 'easeInOutExpo'
                        }, 1000);
                    }


                    //show the operations toolbar
                    selected_member = node.id;
                    st.onClick(node.id);

                    //display data on right container
                    display_data(node);
                };
                //set label styles
                var style = label.style;
                style.width = node.data.$width - 1 + 'px'; // -1 is drop shadow fix
                style.height = node.data.$height - 1 + 'px';
                style.cursor = 'pointer';
                style.fontSize = '0.9em';
                style.textAlign = 'center';
                style.paddingTop = '15px';
            },
            onComplete: function () {
                //alert(selected_member);
            },
            levelsToShow: 2,
            //This method is called right before plotting
            //a node. It's useful for changing an individual node
            //style properties before plotting it.
            //The data properties prefixed with a dollar
            //sign will override the global node style properties.
            onBeforePlotNode: function (node) {
                //add some color to the nodes in the path between the
                //root node and the selected node.
                if (node.selected) {
                    if (node.data.gender == "0") {
                        node.data.$color = '#bbdefb';
                    } else {
                        node.data.$color = "#f8bbd0";
                    }

                } else {

                    delete node.data.$color;
                    //if the node belongs to the last plotted level

                    if (node.data.gender == "0") {
                        node.data.$color = "#e3f2fd";
                    } else {
                        node.data.$color = "#fce4ec";
                    }
                    console.log('We are plotting the node');

                }
            },
            //This method is called right before plotting
            //an edge. It's useful for changing an individual edge
            //style properties before plotting it.
            //Edge data proprties prefixed with a dollar sign will
            //override the Edge global style properties.
            onBeforePlotLine: function (adj) {
                if (adj.nodeFrom.selected && adj.nodeTo.selected) {
                    adj.data.$color = "#eed";
                    adj.data.$lineWidth = 3;
                } else {
                    delete adj.data.$color;
                    delete adj.data.$lineWidth;
                }
            }
        });
        //load json data
        st.loadJSON(json);
        //compute node positions and layout
        st.compute();
        //optional: make a translation of the tree
        // st.geom.translate(new $jit.Complex(-200, 0), "current");
        //emulate a click on the root node.
        st.onClick(st.root, {
            onComplete: function () { // When the onlick animation on root is over the perform select
                if (window.location.hash) {
                    var window_hash = window.location.hash;
                    var split = window_hash.split("#");
                    st.select(split[1]);
                }

                //Display data of root in the right Container
                var tree_root = (st.graph.getNode(st.root));
                display_clear_data();
                display_data(tree_root);
                if (typeof user_id !== 'undefined') showUser(user_id);
            }
        });
        //store the selected member id in selected_member
        selected_member = st.root;


        //end

        tree = st;


        //end
    })
    //Callbacks, if we are unable to get desired reponse
        .fail(function (data, textstatus, error) {
            alert(data.responseText);
        });

}
