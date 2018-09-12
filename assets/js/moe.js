function main() {
    // Set frame dimensions
    d3.select(self.frameElement).style("width", "1800px");
    d3.select(self.frameElement).style("height", "1800px");

    var saved;
    var currKey;
    var authKey = document.getElementById("authKey").value;
    var workFolder = document.getElementById("workFolder").value;
    var externalApp = document.getElementById("externalApp").value;
    var timePointLabels = document.getElementById("timePointLabels").value;

    var rm = 15;
    var obj = [];
    var config = [];
    var timePointNum;
    var rootcount = 0;
    var rootnodes = [];
    var savedNodes = [];
    var rootNodeSize = [];
    var tpConfigArray = [];
    var customImgCount = 0;

    var width = 1;
    var height = 1;
    var zoomOffset = 0;
    var running = false;
    var currentZoom = 1;
    var currentTranslate = ([0, 0]);

    // default configuration file path
    var configFile = "assets/data/config.txt";

    function urlExists(url) {
        var http = new XMLHttpRequest();
        http.open('HEAD', url, false);
        http.send();
        return http.status != 404;
    }

    var customConfig = urlExists(workFolder + "config.txt");
    var customTP = urlExists(workFolder + "temptpConfig.txt");

    if (customConfig == true) {
        configFile = workFolder + "config.txt";
        console.log("Found Custom Config!");
    }

    if (customTP == true) {
        var TPFile = workFolder + "temptpConfig.txt";

        d3.tsv(TPFile, function (error, data) {
            data.forEach(function (d, i) {
                tpConfigArray[i] = {};
                tpConfigArray[i]['LABEL'] = d.label;
                tpConfigArray[i]['IMAGE'] = workFolder + d.image;
                tpConfigArray[i]['DESCRIPTION'] = d.description;
//                console.log(tpConfigArray[i]);
            });
        });
    }

    //Load configuration file
    d3.tsv(configFile, function (error, info) {
        var row = 0;

        info.forEach(function (d) {
            if (row == 0) {
//                console.log("Config File: " + configFile);
//                console.log("d.fontColorCenter = " + d.fontColorCenter);

                config = {
                    "Timepoint"          : +d.timepoint,
                    "charge"             : +d.charge,
                    "gravity"            : +d.gravity,
                    "BorderWidth"        : +d.borderW,
                    "Link-width"         : +d.linkWidth,
                    "Link-color"         : d.linkColor,
                    "max_score"          : +d.maxScore,
                    "min_score"          : +d.minScore,
                    "Radius"             : +d.radius,
                    "FontColor_node"     : d.fontColorNode,
                    "FontColor_center"   : d.fontColorCenter,
                    "colorLabel"         : d.colorLabel,
                    "sizeLabel"          : d.sizeLabel,
                    "typeLabel"          : d.typeLabel,
                    "tpLabels"           : d.tpLabels,
                    "typeColors"         : d.typeColors,
                    "fillColors"         : d.fillColors,
                    "discreteFillColors" : d.discreteFillColors,
                    "bgFlg"              : d.bgFlg,
                    "background"         : d.background
                };

                if (externalApp === 'SysBioCube') {
                    config["BorderWidth"] = 10
                }

//                console.log("File read test = " + +d.linkColor);
//                console.log("config['BorderWidth'] = " + config["BorderWidth"]);
//                console.log("d.borderW = " + d.borderW);

                if (config["bgFlg"] == "" || config["bgFlg"] == null || config["bgFlg"] == 0) {
                    config["bgFlg"] = 1;
                } else if (config["bgFlg"] == 1) {
                    config["bgFlg"] = 0;
                }

                if (config["background"] == "" || config["background"] == null || config["background"] == 0) {
                    config["background"] = 1;
                } else if (config["background"] == 1) {
                    config["background"] = 0;
                }

                savedNodes[row] = {
                    "rootId" : d.rootId,
                    "rootX"  : d.rootX,
                    "rootY"  : d.rootY
                };

                if (d.saved == "yes" && authKey !== d.key) {
                    d3.select("#save_config")
                        .attr("disabled", "disabled")
                        .style("cursor", "not-allowed");
                    d3.select("#saveConfigDisable")
                        .attr("title", "Not Authorized");
                    d3.select("#reset_config")
                        .attr("disabled", "disabled")
                        .style("cursor", "not-allowed");
                    d3.select("#resetConfigDisable")
                        .attr("title", "Not Authorized");
                    d3.select("#download")
                        .attr("disabled", "disabled")
                        .style("cursor", "not-allowed");
                    d3.select("#downloadDisable")
                        .attr("title", "Not Authorized");
                    d3.select("#editableLink")
                        .attr("disabled", "disabled")
                        .style("cursor", "not-allowed");
                    d3.select("#editableLinkDisable")
                        .attr("title", "Not Authorized");
                    d3.select("#lockedLink")
                        .attr("disabled", "disabled")
                        .style("cursor", "not-allowed");
                    d3.select("#lockedLinkDisable")
                        .attr("title", "Not Authorized");
                }

                if (d.saved == "yes") {
                    currKey = d.key;
                } else {
                    currKey = Math.floor(100000 + Math.random() * 900000)
                }
            } else {
                savedNodes[row] = {
                    "rootId" : d.rootId,
                    "rootX"  : d.rootX,
                    "rootY"  : d.rootY
                }
            }

            row++;
        });

        d3.tsv(workFolder + "nodes.txt", function (error, nodeSet) {
            nodeSet.forEach(function (d) {
                // Specify default shape (circle) if not user-defined
                if (typeof eval('d.shape') == 'undefined') {
                    d.shape = 0;
                }

                // Specify default size if not user-defined
                if (typeof eval('d.size') == 'undefined') {
                    d.size = 2;
                }

                // Specify default type if not user-defined
                if (typeof eval('d.type') == 'undefined') {
                    d.type = "Default Type";
                }

                d.color1 = d.color;
                d.type1 = d.type;
                d.size1 = d.size;
                d.size = d.size * 6;
                d.group1 = d.group;

                if (d.root == "true") {
                    if (d.icon == (d.id + ".jpg")) {
                        customImgCount++;
                    }

                    d.fixed = true;

                    var obj = savedNodes.filter(function (obj) {
                        return obj.rootId === d.id;
                    })[0];

                    if (obj !== 'undefined' && customConfig == true) {
                        d.x = Math.floor(obj.rootX);
                        d.y = Math.floor(obj.rootY);
                    } else {
                        d.x = (((rootcount + 1) % 7) * 1600 / 6) + 400 / 2.5;
                        d.y = (Math.floor((rootcount + 1) / 7) * 2000 / 8) + 500 / 2.5;
                    }

                    rootnodes[rootcount] = d.id;
                    rootNodeSize[d.id] = d.type * 3;
                    rootcount++;
                } else {
                    var profileArray = [];
                    var y;

                    for (var i = 1; i <= +nodeSet[1].timepoints; i++) {
                        if (typeof eval('d.color' + i) !== 'undefined') {
                            y = eval('d.color' + i) * 5;
                        } else {
                            y = d.color * 5;
                        }
                        profileArray.push({x : i * 10, y : y});
                    }

                    d.all = profileArray;
                }

                timePointNum = +nodeSet[1].timepoints;
            });

            if (customImgCount < 1) {
                d3.select("#hideimg")
                    .attr("disabled", "disabled")
                    .style("cursor", "not-allowed");
                d3.select("#hideimgDisable")
                    .attr("title", "No Custom Images Uploaded");
            }

            if (timePointNum < 2) {
                d3.select("#animate")
                    .attr("disabled", "disabled")
                    .style("cursor", "not-allowed");
                d3.select("#animateDisable")
                    .attr("title", "No Animation Data Available");
                d3.select("#stopAnimate")
                    .attr("disabled", "disabled")
                    .style("cursor", "not-allowed");
                d3.select("#stopAnimateDisable")
                    .attr("title", "No Animation Data Available");
                d3.select("#profile")
                    .attr("disabled", "disabled")
                    .style("cursor", "not-allowed");
                d3.select("#profileDisable")
                    .attr("title", "No Profile Data Available");
            }

            d3.tsv(workFolder + "links.txt", function (error, linkSet) {
                linkSet.forEach(function (d) {
                    d.sourceId1 = d.sourceId;
                    d.targetId1 = d.targetId;

                    // Specify default marker shapes if not user-defined
                    if (typeof eval('d.marker_start') == 'undefined') {
                        d.marker_start = 3;
                    }

                    if (typeof eval('d.marker_end') == 'undefined') {
                        d.marker_end = 2;
                    }

                    // Specify default link_scale if not user-defined
                    if (typeof eval('d.link_scale') == 'undefined') {
                        d.link_scale = 1;
                    }

                    obj[d.targetId] = (obj[d.targetId] || 0) + 1;
                });

                var strtime;
                var discrete_color = 1;
                var min_score = config["min_score"];
                var max_score = config["max_score"];
                var colorScale_type = d3.scale.category10();
                var color_bar = ['min', 'per25', 'median', 'per75', 'max'];

                if (config["tpLabels"] == "" || config["tpLabels"] == null) {
                    strtime = timePointLabels.split(',');
                } else {
                    strtime = config["tpLabels"].split(',');
                }

                var color_ha = function () {
                    this.min = "#1a9641";
                    this.per25 = "#a6d96a";
                    this.median = "#ffffbf";
                    this.per75 = "#fdae61";
                    this.max = "#d7191c";
                };

                var color_hash = new color_ha();

                if (config["fillColors"] == "" || config["fillColors"] == null) {
                    color_hash = new color_ha();
                } else {
                    var savedFillColors = config["fillColors"].split(",");
                    color_hash.min = savedFillColors[0];
                    color_hash.per25 = savedFillColors[1];
                    color_hash.median = savedFillColors[2];
                    color_hash.per75 = savedFillColors[3];
                    color_hash.max = savedFillColors[4];
                }

                var colorScale = d3.scale.linear()
                    .domain(
                        [
                            min_score,
                            (3 * min_score + max_score) / 4,
                            (min_score + max_score) / 2,
                            (min_score + 3 * max_score) / 4,
                            max_score
                        ]
                    )
                    .range(
                        [
                            color_hash.min,
                            color_hash.per25,
                            color_hash.median,
                            color_hash.per75,
                            color_hash.max
                        ]
                    )
                    .clamp(true)
                    .nice();

                var colorScale_dis = d3.scale.category10();

                function color_choice() {
                    if (discrete_color == 0) {
                        colorScale = d3.scale.linear()
                            .domain(
                                [
                                    min_score,
                                    (3 * min_score + max_score) / 4,
                                    (min_score + max_score) / 2,
                                    (min_score + 3 * max_score) / 4,
                                    max_score
                                ]
                            )
                            .range(
                                [
                                    color_hash.min,
                                    color_hash.per25,
                                    color_hash.median,
                                    color_hash.per75,
                                    color_hash.max
                                ]
                            )
                            .clamp(true)
                            .nice();
                    } else {
                        colorScale_dis = d3.scale.category10();
                    }
                }

                var type_hash = [];
                var color_hash_dis = [];

                var typeMouseOver = function () {
                    var thisObject = d3.select(this);
                    var typeValue = thisObject.attr("type_value");
                    var strippedTypeValue = typeValue.replace(/ /g, "_");

                    var legendBulletSelector = "." + "legendBullet-" + strippedTypeValue;
                    var selectedBullet = d3.selectAll(legendBulletSelector);
                    selectedBullet.attr("r", 1.2 * 6);

                    var legendTextSelector = "." + "legendText-" + strippedTypeValue;
                    var selectedLegendText = d3.selectAll(legendTextSelector);
                    selectedLegendText.style("font", "bold 12px Verdana");
                    selectedLegendText.style("fill", "Maroon");

                    var nodeTextSelector = "." + "nodeText-" + strippedTypeValue;
                    var selectedNodeText = d3.selectAll(nodeTextSelector);
                    selectedNodeText.style("font", "bold 12px Verdana");
                    selectedNodeText.style("fill", "Maroon");

                    var nodeCircleSelector = "." + "nodeCircle-" + strippedTypeValue;
                    var selectedCircle = d3.selectAll(nodeCircleSelector);
                    selectedCircle.style("stroke", "Maroon");
                };

                var typeMouseOut = function () {
                    var thisObject = d3.select(this);
                    var typeValue = thisObject.attr("type_value");
                    var strippedTypeValue = typeValue.replace(/ /g, "_");

                    var legendBulletSelector = "." + "legendBullet-" + strippedTypeValue;
                    var selectedBullet = d3.selectAll(legendBulletSelector);
                    selectedBullet.attr("r", 6);

                    var legendTextSelector = "." + "legendText-" + strippedTypeValue;
                    var selectedLegendText = d3.selectAll(legendTextSelector);
                    selectedLegendText.style("font", "normal 12px Verdana");

                    if (config["bgFlg"] == 1) {
                        selectedLegendText.style("fill", "White");
                    } else {
                        selectedLegendText.style("fill", "Black");
                    }

                    var nodeTextSelector = "." + "nodeText-" + strippedTypeValue;
                    var selectedNodeText = d3.selectAll(nodeTextSelector);
                    selectedNodeText.style("font", "normal 11px Verdana");

                    if (config["bgFlg"] == 1) {
                        selectedNodeText.style("fill", config["FontColor_node"]);
                    } else if (config["bgFlg"] == 0) {
                        selectedNodeText.style("fill", config["FontColor_node"]);
                    }

                    var nodeCircleSelector = "." + "nodeCircle-" + strippedTypeValue;
                    var selectedCircle = d3.selectAll(nodeCircleSelector);
                    selectedCircle.style("stroke", function (d) {
                        return type_hash[d.type];
                    });
                };

                var nodeMouseOver = function () {
                    var thisObject = d3.select(this);
                    var typeValue = thisObject.attr("type_value");
                    var strippedTypeValue = typeValue.replace(/ /g, "_");

                    d3.select(this).select("#nodeShape")
                        .transition()
                        .duration(250)
                        .attrTween("transform", function (d, i, a) {
                            return d3.interpolateString(a, 'scale(1.8)');
                        })
                        // Give the node strokes some thickness
                        .style("stroke-width", function (d) {
                            if (d.root == "true") {
                                return 2;
                            } else {
                                return 2;
                            }
                        });

                    d3.select(this).select("text").transition()
                        .duration(250)
                        .style("font-weight", "bold")
                        .style("font-size", "20px")
                        .attr("fill", function (d) {
                            if (d.root == "true") {
                                return config["FontColor_center"];
                            } else {
                                return config["FontColor_node"];
                            }
                        });

                    var legendBulletSelector = "." + "legendBullet-" + strippedTypeValue;
                    var selectedBullet = d3.selectAll(legendBulletSelector);
                    selectedBullet.attr("r", 1.2 * 6);

                    var legendTextSelector = "." + "legendText-" + strippedTypeValue;
                    var selectedLegendText = d3.selectAll(legendTextSelector);
                    selectedLegendText.style("font", "bold 12px Verdana");
                    selectedLegendText.style("fill", "Maroon");

                    if (this.classList.contains("selectedNode")) {
                        $('circle[class*=selectedNodeCircle]').attr("fill", "Maroon");
                    }
                };

                var nodeMouseOut = function () {
                    var thisObject = d3.select(this);
                    var typeValue = thisObject.attr("type_value");
                    var strippedTypeValue = typeValue.replace(/ /g, "_");

                    d3.select(this).select("#nodeShape")
                        .transition()
                        .duration(250)
                        .attrTween("transform", function (d, i, a) {
                            return d3.interpolateString(a, 'scale(1)');
                        })
                        .style("stroke-width", function (d) {
                            if (d.root == "true") {
                                return 2;
                            } else {
                                return config["BorderWidth"];
                            }
                        });

                    d3.select(this).select("text").transition()
                        .duration(250)
                        .style("font-weight", function (d) {
                            if (d.root == "true") {
                                return "bold";
                            } else {
                                return "normal";
                            }
                        })
                        .style("font-size", function (d) {
                            if (d.root == "true") {
                                return "14px";
                            } else {
                                return "11px";
                            }
                        })
                        .attr("fill", function (d) {
                            if (d.root == "true") {
                                return config["FontColor_center"];
                            } else {
                                return config["FontColor_node"];
                            }
                        })
                        .text(function (d) {
                            var rootid = d.name;
                            return rootid.substring(0, 30);
                        });

                    var legendBulletSelector = "." + "legendBullet-" + strippedTypeValue;
                    var selectedBullet = d3.selectAll(legendBulletSelector);
                    selectedBullet.attr("r", 6);

                    var legendTextSelector = "." + "legendText-" + strippedTypeValue;
                    var selectedLegendText = d3.selectAll(legendTextSelector);
                    selectedLegendText.style("font", "normal 12px Verdana");

                    if (config["bgFlg"] == 1) {
                        selectedLegendText.style("fill", "White");
                    } else if (config["bgFlg"] == 0) {
                        selectedLegendText.style("fill", "Black");
                    }

                    if (customImgCount > 0 && hideimage == 0) {
                        customImg();
                    }

                    if (this.classList.contains("selectedNode")) {
                        $('circle[class*=selectedNodeCircle]').attr("fill", "yellow");
                    }
                };

                var mouseX;
                var mouseY;
                $(document).mousemove(function (e) {
                    mouseX = e.pageX;
                    mouseY = e.pageY;
                });

                $('.pop').mouseleave(function () {
                    $('.pop').fadeOut(0);
                });

                // Create a hash that maps colors to types...
                nodeSet.forEach(function (d) {
                    // exclude root nodes from color hash
                    if (d.root != "true") {
                        for (i = 1; i <= +nodeSet[1].timepoints; i++) {
                            if (typeof eval('d.type' + i) !== 'undefined') {
                                type_hash[eval('d.type' + i)] = eval('d.type' + i);
                            }

                            if (typeof eval('d.color' + i) !== 'undefined') {
                                if (config["discreteFillColors"] == "" || config["discreteFillColors"] == null) {
                                    color_hash_dis[eval('d.color' + i)] = eval('d.color' + i);
                                }
                            }
                        }
                    }
                });

                if (config["discreteFillColors"] == "" || config["discreteFillColors"] == null) {
                } else {
                    var savedDiscreteFillColors = config["discreteFillColors"].split(",");
                    color_hash_dis = savedDiscreteFillColors.slice();
                }

                function keys(obj) {
                    var keys = [];

                    for (var key in obj) {
                        if (obj.hasOwnProperty(key)) {
                            keys.push(key);
                        }
                    }

                    return keys;
                }

                var sortedKeys_color = keys(color_hash_dis).sort();
                sortedKeys_color.forEach(function (d, i) {
                    if (config["discreteFillColors"] == "" || config["discreteFillColors"] == null) {
                        color_hash_dis[d] = colorScale_dis(i);
                    }
                });

                var sortedKeys_type = keys(type_hash).sort();
                if (config["typeColors"] == "" || config["typeColors"] == null) {
                    sortedKeys_type.forEach(function (d, i) {
                        type_hash[d] = colorScale_type(i);
                    });
                } else {
                    var savedColors = config["typeColors"].split(",");
                    sortedKeys_type.forEach(function (d, i) {
                        type_hash[d] = savedColors[i];
                    });
                }

                // Setup GUI Controls
                var gui = new dat.GUI({autoPlace : true, closed : true});
                gui.close();

                var fl = gui.addFolder('Graph Controls');
                fl.open();

                var groups = [];
                var tpnum = +nodeSet[1].timepoints;
                if (timePointNum > 1) {
                    var timePoint = fl.add(config, "Timepoint", 1, tpnum).step(1).listen();
                    timePoint.onChange(function (value) {
                        nodeSet.forEach(function (d) {
                            if (d.root == "false") {
                                if (typeof eval('d.type' + value) !== 'undefined') {
                                    d.type = eval('d.type' + value);
                                }
                                if (typeof eval('d.size' + value) !== 'undefined') {
                                    d.size = (eval('d.size' + value)) * 6;
                                }
                                if (typeof eval('d.color' + value) !== 'undefined') {
                                    d.color = eval('d.color' + value); //Ruoting changed
                                }
                                if (typeof eval('d.group' + value) !== 'undefined') {
                                    var validGroup = rootnodes.indexOf(eval('d.group' + value));
                                    if (validGroup >= 0) {
                                        groups[d.id] = eval('d.group' + value);
                                    }
                                }
                            }
                        });

                        linkSet.forEach(function (d) {
                            var idx = rootnodes.indexOf(d.targetId);
                            var ids = rootnodes.indexOf(d.sourceId);

                            if ((idx >= 0) && (ids >= 0)) {
                                if (typeof eval('d.sourceId' + value) !== 'undefined') {
                                    d.source = node_hash[eval('d.sourceId' + value)];
                                }
                                if (typeof eval('d.targetId' + value) !== 'undefined') {
                                    d.target = node_hash[eval('d.targetId' + value)];
                                }
                            } else if (idx >= 0) {
                                d.target = node_hash[groups[d.sourceId]];
                            } else if ((idx < 0) && (ids < 0)) {
                                if (typeof eval('d.sourceId' + value) !== 'undefined') {
                                    d.source = node_hash[eval('d.sourceId' + value)];
                                }
                                if (typeof eval('d.targetId' + value) !== 'undefined') {
                                    d.target = node_hash[eval('d.targetId' + value)];
                                }
                            }
                        });

                        timePointLabel.text('Time point:   ' + strtime[value - 1]);

                        if (tpConfigArray.length > 0) {
                            tpImg.attr("src", tpConfigArray[value - 1]['IMAGE']);
                            tpDesc.text(tpConfigArray[value - 1]['DESCRIPTION']);
                        }

                        updateTimepoint(750);
                    });
                }

                var animate;
                var count = 1;

                function update() {
                    //d3.selectAll(".node").transition().duration(250).attr("opacity", 0.3);
                    d3.selectAll(".link").transition().duration(250).attr("opacity", 0.5);
                    if (count < (+nodeSet[1].timepoints + 1)) {
                        timePointLabel.text('Time point:   ' + strtime[count - 1]);

                        if (tpConfigArray.length > 0) {
                            tpImg.attr("src", tpConfigArray[count - 1]['IMAGE']);
                            tpDesc.text(tpConfigArray[count - 1]['DESCRIPTION']);
                        }

                        config.Timepoint = count;
                    } else {
                        count = 1;
                        timePointLabel.text('Time point:   ' + strtime[count - 1]);
                        if (tpConfigArray.length > 0) {
                            tpImg.attr("src", tpConfigArray[count - 1]['IMAGE']);
                            tpDesc.text(tpConfigArray[count - 1]['DESCRIPTION']);
                        }
                        config.Timepoint = count;
                    }

                    nodeSet.forEach(function (d) {
                        var nodeID = d.id;
                        var nodeClass = nodeID.replace(/ /g, "_");
                        if (d.root == "false") {
                            if (typeof eval('d.type' + count) !== 'undefined') {
                                /*var currType = d.type;
                                 if (currType == eval('d.type' + count)) {
                                 d3.select("." + d.id).attr("opacity", 0.3);
                                 } else {
                                 d3.select("." + d.id).attr("opacity", 0.9);
                                 }*/
                                d.type = eval('d.type' + count);
                            }
                            if (typeof eval('d.size' + count) !== 'undefined') {
                                /*var currSize = d.size;
                                 if (currSize == eval('d.size' + count)) {
                                 d3.select("." + d.id).attr("opacity", 0.3);
                                 } else {
                                 d3.select("." + d.id).attr("opacity", 0.9);
                                 }*/
                                d.size = (eval('d.size' + count)) * 6;
                            }
                            if (typeof eval('d.color' + count) !== 'undefined') {
                                var currColor = d.color;
                                var difference = Math.abs(currColor - (eval('d.color' + count)));

                                if (sensitivityDelta == 0) {
                                    d3.select("." + nodeClass).transition().duration(500).attr("opacity", 0.9);
                                } else if (difference < sensitivityDelta) {
                                    //console.log("No color change!");
                                    d3.select("." + nodeClass).transition().duration(250).attr("opacity", 0.3);
                                } else if (difference > sensitivityDelta) {
                                    //console.log("Color change!");
                                    d3.select("." + nodeClass).transition().duration(500).attr("opacity", 0.9);
                                }
                                d.color = eval('d.color' + count); //Ruoting changed
                            }
                            if (typeof eval('d.group' + count) !== 'undefined') {
                                /*var currGroup = groups[d.id];
                                 if (currGroup == eval('d.group' + count)) {
                                 d3.select("." + d.id).attr("opacity", 0.3);
                                 } else {
                                 d3.select("." + d.id).attr("opacity", 0.9);
                                 }*/
                                var validGroup = rootnodes.indexOf(eval('d.group' + count));
                                if (validGroup >= 0) {
                                    groups[d.id] = eval('d.group' + count);
                                }
                            }
                        }
                    });

                    linkSet.forEach(function (d) {
                        var idx = rootnodes.indexOf(d.targetId);
                        var ids = rootnodes.indexOf(d.sourceId);

                        if ((idx >= 0) && (ids >= 0)) {
                            if (typeof eval('d.sourceId' + count) !== 'undefined') {
                                d.source = node_hash[eval('d.sourceId' + count)];
                            }
                            if (typeof eval('d.targetId' + count) !== 'undefined') {
                                d.target = node_hash[eval('d.targetId' + count)];
                            }
                        } else if (idx >= 0) {
                            d.target = node_hash[groups[d.sourceId]];
                        } else if ((idx < 0) && (ids < 0)) {
                            if (typeof eval('d.sourceId' + count) !== 'undefined') {
                                d.source = node_hash[eval('d.sourceId' + count)];
                            }
                            if (typeof eval('d.targetId' + count) !== 'undefined') {
                                d.target = node_hash[eval('d.targetId' + count)];
                            }
                        }
                    });
                    updateTimepoint();
                    count++;
                }

                if (timePointNum > 1) {
                    config["Speed"] = 3;
                    var animateSpeed = fl.add(config, "Speed", 1, 5).step(1);
                    animateSpeed.onChange(function (value) {
                        if (running === true) {
                            clearInterval(animate);
                            animate = setInterval(update, 3500 / Math.sqrt(value));
                            config["Speed"] = value;
                        }
                    });
                }

                var sensitivityDelta = 0.5;
                config["Sensitivity"] = 0.5;
                var sensitivity = fl.add(config, "Sensitivity", 0, 1).step(0.001);
                sensitivity.onChange(function (value) {
                    sensitivityDelta = value;
                });

                var border = fl.add(config, "BorderWidth", 0, 10).step(1);
                border.onChange(function (value) {
                    var nodeSelector = "#" + "nodeShape";
                    var selectedNode = d3.selectAll(nodeSelector);
                    selectedNode.style("stroke-width", function (d) {
                        if (d.root == "true") {
                            return 2;
                        } else {
                            return value;
                        }
                    });
                });

                var linkStroke = fl.add(config, "Link-width", 1, 10);
                linkStroke.onChange(function (value) {
                    var linkSelector = "." + "link";
                    var selectedLink = d3.selectAll(linkSelector);
                    selectedLink.style("stroke-width", function (d) {
                        var idx = rootnodes.indexOf(d.targetId);
                        if ((rootnodes.indexOf(d.targetId) >= 0) && (rootnodes.indexOf(d.sourceId) >= 0)) {
                            return value * +d.link_scale;
                        } else if (idx >= 0) {
                            return value * +d.link_scale;
                        } else
                            return value * +d.link_scale;
                    });
                });

                var radius_m = fl.add(config, "Radius", 5, 100);
                radius_m.onChange(function (value) {
                    rm = value;
                    force.linkDistance(function (d) {
                        return Math.min(Math.max(((rm * obj[d.targetId]) / 3.14), 10 + rm), 200);
                    }) // Controls edge length
                        .start();
                });

                var linkColor = fl.addColor(config, "Link-color");
                linkColor.onChange(function (value) {
                    var linkSelector = ".link.nLink";
                    var selectedLink = d3.selectAll(linkSelector);
                    selectedLink.style("stroke", function (d) {
                        if (d.linkColor == "" || d.linkColor == null) {
                            return value;
                        } else {
                            return d.linkColor;
                        }
                    });
                });

                var FontColor_node = fl.addColor(config, "FontColor_node");
                FontColor_node.onChange(function (value) {
                    $('[class^="nodeText"]').css("fill", value);
                    //Fontnode.attr("fill", function(d) { if(d.root!=="true") {return value;} else {return config["FontColor_center"];} })
                });

                var FontColor_center = fl.addColor(config, "FontColor_center");
                FontColor_center.onChange(function (value) {
                    $('[class="focalNodeText"]').css("fill", value);
                    //Fontnode.attr("fill", function(d) { if(d.root=="true") {return value;} else {return config["FontColor_node"];} })
                });

                var cl = gui.addFolder('Type Colors (outline)');
                cl.open();

                for (var x in type_hash) {
                    var colorChanger = cl.addColor(type_hash, x);
                    colorChanger.onChange(function (value) {
                        type_hash[this.property] = value;
                        var typeValue = this.property;
                        var strippedTypeValue = typeValue.replace(/ /g, "_");
                        var legendBulletSelector = "." + "legendBullet-" + strippedTypeValue;
                        var selectedBullet = d3.selectAll(legendBulletSelector);
                        selectedBullet.style("stroke", value);

                        var nodeCircleSelector = "." + "nodeCircle-" + strippedTypeValue;
                        var selectedCircle = d3.selectAll(nodeCircleSelector);
                        selectedCircle.style("stroke", value);
                    });
                }

                var cl1 = gui.addFolder('Color bar (fill)');
                cl1.open();

                var max_sc = cl1.add(config, "max_score", 0, 10);
                max_sc.onChange(function (value) {
                    max_score = value;
                    config["max_score"] = value;
                    colorScale.domain([min_score, (3 * min_score + max_score) / 4, (min_score + max_score) / 2, (min_score + 3 * max_score) / 4, max_score])
                        .range([color_hash.min, color_hash.per25, color_hash.median, color_hash.per75, color_hash.max])
                        .clamp(true)
                        .nice();
                    updateTimepoint(750);
                    grad();
                });

                var min_sc = cl1.add(config, "min_score", -10, 0);
                min_sc.onChange(function (value) {
                    min_score = value;
                    config["min_score"] = value;
                    colorScale.domain([min_score, (3 * min_score + max_score) / 4, (min_score + max_score) / 2, (min_score + 3 * max_score) / 4, max_score])
                        .range([color_hash.min, color_hash.per25, color_hash.median, color_hash.per75, color_hash.max])
                        .clamp(true)
                        .nice();
                    updateTimepoint(750);
                    grad();
                });

                for (x = 0; x < 5; x++) {
                    var colorChanger1 = cl1.addColor(color_hash, color_bar[x]);
                    colorChanger1.onChange(function (value) {
                        color_hash[this.property] = value;
                        colorScale.range([color_hash.min, color_hash.per25, color_hash.median, color_hash.per75, color_hash.max]);
                        d3.selectAll(".gradrect").transition().duration(250).style("opacity", 0.9);
                        updateTimepoint(750);
                        grad();
                        setTimeout(function () {
                            d3.selectAll(".gradrect").transition().duration(250).style("opacity", 1);
                        }, 1000);
                    });
                }

                // Add colors to original node records...
                nodeSet.forEach(function (d) {
                    d.color_type = type_hash[d.type];
                });

                var adjustedZoom;

                var zoom = d3.behavior.zoom()
                    .scaleExtent([0.5, 3])
                    .on("zoom", zoomed);

                function zoomed() {
                    adjustedZoom = zoom.scale() + zoomOffset;
                    svgCanvas.attr("transform", "translate(" + zoom.translate() + ")" + " scale(" + adjustedZoom + ")");
                    currentZoom = zoom.scale() + zoomOffset;
                    currentTranslate = zoom.translate();
                }

                // Create a canvas...
                var svgCanvas = d3.select("div.chart")
                    .append("svg")
                    .attr("id", "mysvg")
                    .call(zoom)
                    .append("g")
                    .attr("class", "focalNodeCanvas");

                var data = [
                    {
                        id      : 0,
                        name    : 'circle',
                        path    : 'M 0, 0  m -5, 0  a 5,5 0 1,0 10,0  a 5,5 0 1,0 -10,0',
                        viewbox : '-6 -6 12 12'
                    }
                    , {id : 1, name : 'square', path : 'M 0,0 m -5,-5 L 5,-5 L 5,5 L -5,5 Z', viewbox : '-5 -5 10 10'}
                    , {id : 2, name : 'arrow', path : 'M 0,0 m -5,-5 L 5,0 L -5,5 Z', viewbox : '-5 -5 10 10'}
                    , {id : 3, name : 'stub', path : 'M 0,0 m -1,-5 L 1,-5 L 1,5 L -1,5 Z', viewbox : '-1 -5 2 10'}
                    , {id : 4, name : 'barrow', path : 'M 0,0 L 10, -5 L 10, 5 Z', viewbox : '0 -5 10 10'}
                ];

                var marker = svgCanvas.append('svg:defs').selectAll('marker')
                    .data(data)
                    .enter()
                    .append('svg:marker')
                    .attr('id', function (d) {
                        return 'marker_' + d.name
                    })
                    .attr('markerHeight', 5)
                    .attr('markerWidth', 5)
                    .attr('markerUnits', 'strokeWidth')
                    .attr('orient', 'auto')
                    .attr('refX', 0)
                    .attr('refY', 0)
                    .attr('viewBox', function (d) {
                        return d.viewbox
                    })
                    .append('svg:path')
                    .attr('d', function (d) {
                        return d.path
                    })
                    .attr('fill', config['Link-color']);

                var node_hash = [];
                var typevalue_hash = [];

                // Create a hash that allows access to each node by its id
                nodeSet.forEach(function (d) {
                    node_hash[d.id] = d;
                    typevalue_hash[d.type] = d.type;
                });

                // Append the source object node and the target object node to each link records...
                linkSet.forEach(function (d) {
                    d.source = node_hash[d.sourceId];
                    d.target = node_hash[d.targetId];

                    if (d.root == "true") {
                        d.direction = "OUT";
                    } else {
                        d.direction = "IN";
                    }
                });

                // Create a force layout and bind Nodes and Links
                var force = d3.layout.force()
                    .nodes(nodeSet)
                    .links(linkSet)
                    .charge(-3000)
                    .chargeDistance(50)
                    .gravity(0)
                    .friction(0.05)
                    .alpha(0.01)
                    .linkStrength(function (d) {
                        var idx = rootnodes.indexOf(d.targetId);
                        if ((rootnodes.indexOf(d.targetId) >= 0) && (rootnodes.indexOf(d.sourceId) >= 0)) {
                            return 0;
                        } else if (idx >= 0) {
                            return 5;
                        } else
                            return 0;
                    })
                    .linkDistance(function (d) {
                        return Math.min(Math.max(((config["Radius"] * obj[d.targetId]) / 3.14), 10 + config["Radius"]), 200);
                    }) // Controls edge length
                    .on("tick", tick)
                    .start();

                // Draw lines for Links between Nodes
                var link = svgCanvas.selectAll(".gLink")
                    .data(force.links())
                    .enter().append("g")
                    .attr("class", "gLink")
                    .append("line")
                    .attr("class", function (d) {
                        var idt = rootnodes.indexOf(d.targetId);
                        var ids = rootnodes.indexOf(d.sourceId);

                        if (idt >= 0 && ids >= 0) {
                            return "link nLink";
                        } else if (idt >= 0) {
                            return "link rLink";
                        } else {
                            return "link nLink";
                        }
                    })
                    .style('marker-start', function (d) {
                        return 'url(#marker_' + data[d.marker_start].name + ')';
                    })
                    .style('marker-end', function (d) {
                        return 'url(#marker_' + data[d.marker_end].name + ')';
                    })
                    .style("stroke-width", function (d) {
                        var idt = rootnodes.indexOf(d.targetId);
                        var ids = rootnodes.indexOf(d.sourceId);

                        if (idt >= 0 && ids >= 0) {
                            return config["Link-width"] * +d.link_scale;
                        } else if (idt >= 0) {
                            return 0;
                        } else
                            return config["Link-width"] * +d.link_scale;
                    })
                    .style("stroke", function (d) {
                        var rootTarget = rootnodes.indexOf(d.targetId);
                        var rootSource = rootnodes.indexOf(d.sourceId);

                        if (rootTarget >= 0 && rootSource >= 0) {
                            if (d.linkColor == "" || d.linkColor == null) {
                                return config["Link-color"];
                            } else {
                                return d.linkColor;
                            }
                        } else if (rootTarget >= 0) {
                            return "none";
                        } else {
                            if (d.linkColor == "" || d.linkColor == null) {
                                return config["Link-color"];
                            } else {
                                return d.linkColor;
                            }
                        }
                    });

                var line = d3.svg.line()
                    .x(function (d) {
                        return d.x - 5;
                    })
                    .y(function (d) {
                        return -d.y + 25;
                    })
                    .interpolate("linear");

                // Create Nodes
                var node = svgCanvas.selectAll(".node")
                    .data(force.nodes())
                    .enter().append("g")
                    .attr("id", function (d) {
                        return d.id;
                    })
                    .attr("class", function (d) {
                        var nodeID = d.id;
                        return "node " + nodeID.replace(/ /g, "_");
                    })
                    .attr("type_value", function (d, i) {
                        return d.type;
                    })
                    .attr("color_value_type", function (d, i) {
                        return type_hash[d.type];
                    })
                    .attr("color_value", function (d, i) {
                        return d.color;
                    })
                    .attr("color_value_color", function (d, i) {
                        return colorScale(+d.color);
                    })
                    .on("mouseover", nodeMouseOver)
                    .on("mouseout", nodeMouseOut)
                    .on('dblclick', connectedNodes) //Added code
                    .call(force.drag())
                    .on("mousedown", function () {
                        d3.event.stopPropagation();
                    })
                    .append("a")
                    .attr("xlink:href", function (d) {
                        return d.hlink;
                    });

                var prof = node.append("path")
                    .attr("d", function (d) {
                        if (d.root == 'false') {
                            return line(d.all);
                        }
                    })
                    .attr('stroke', 'green')
                    .attr('stroke-width', 0)
                    .attr('fill', 'none');

                var cl2 = gui.addFolder('Discrete Colors (fill)');
                cl2.open();

                var discreteColArray = [];
                discreteColArray = color_hash_dis;

                for (x in color_hash_dis) {
                    var colorChanger2 = cl2.addColor(color_hash_dis, x);
                    colorChanger2.onChange(function (value) {
                        color_hash_dis[this.property] = value;
                        discreteColArray[this.property] = value;
                        updateTimepoint(750);

                        var discreteColors = svgCanvas.append("svg:defs")
                            .append("svg:linearGradient")
                            .attr("id", "discreteCols")
                            .attr("x1", "0%")
                            .attr("y1", "0%")
                            .attr("x2", "100%")
                            .attr("y2", "0%");
                        var discreteCount = color_hash_dis.length;
                        for (var i = 0; i <= discreteCount; i++) {
                            discreteColors.append("svg:stop")
                                .attr("offset", ((i / discreteCount) * 100) + "%")
                                .attr("stop-color", discreteColArray[i])
                                .attr("stop-opacity", 1);
                            discreteColors.append("svg:stop")
                                .attr("offset", (((i + 1) / discreteCount) * 100) + "%")
                                .attr("stop-color", discreteColArray[i])
                                .attr("stop-opacity", 1);
                        }
                        d3.selectAll(".gradrect")
                            .attr("width", 20 * discreteCount)
                            .attr("fill", "url(#discreteCols)");
                        $(".gradrect").hide().fadeIn('fast');
                    });
                }
                $(cl2.domElement).attr("hidden", true);

                function discrete() {
                    if (discrete_color == 1) {
                        $(cl1.domElement).attr("hidden", true);
                        color_choice();

                        var discreteCount = discreteColArray.length;
                        var discreteColors = svgCanvas.append("svg:defs")
                            .append("svg:linearGradient")
                            .attr("id", "discreteCols")
                            .attr("x1", "0%")
                            .attr("y1", "0%")
                            .attr("x2", "100%")
                            .attr("y2", "0%");
                        for (var i = 0; i <= discreteCount; i++) {
                            discreteColors.append("svg:stop")
                                .attr("offset", ((i / discreteCount) * 100) + "%")
                                .attr("stop-color", discreteColArray[i])
                                .attr("stop-opacity", 1);
                            discreteColors.append("svg:stop")
                                .attr("offset", (((i + 1) / discreteCount) * 100) + "%")
                                .attr("stop-color", discreteColArray[i])
                                .attr("stop-opacity", 1);
                        }

                        d3.selectAll(".colorBarSvg")
                            .attr("width", (20 * discreteCount) + (-1 * (width / 2 - 10)));
                        var select = d3.selectAll(".gradrect")
                            .attr("width", 20 * discreteCount)
                            .attr("fill", "url(#discreteCols)");
                        $(cl2.domElement).attr("hidden", false);
                        discrete_color = 0;

                        var x = d3.scale.linear()
                            .domain([0, (discreteCount - 1)])
                            .range([0, 20 * (discreteCount - 1)]);

                        var xAxis = d3.svg.axis()
                            .scale(x)
                            .orient("bottom")
                            .ticks(discreteCount)
                            .tickSize(20, 0);

                        d3.select(".colorBarSvg").append("g")
                            .attr("class", "x-axis")
                            .attr("transform", "translate(10," + 29 + ")")
                            .call(xAxis)
                            .selectAll(".tick text")
                            .style("text-anchor", "start")
                            .attr("x", 6)
                            .attr("y", 6);

                        $(".region").hide();
                        d3.selectAll(".region").style("fill", function () {
                            if (config["bgFlg"] == 1) {
                                return "White"
                            } else {
                                return "Black"
                            }
                        });
                        d3.selectAll(".x-axis").style("fill", function () {
                            if (config["bgFlg"] == 1) {
                                return "White"
                            } else {
                                return "Black"
                            }
                        });
                    } else {
                        color_choice();
                        $(cl1.domElement).attr("hidden", false);
                        $(cl2.domElement).attr("hidden", true);
                        $(".region").show();
                        $(".x-axis").hide();
                        d3.selectAll(".region").style("fill", function () {
                            if (config["bgFlg"] == 1) {
                                return "White"
                            } else {
                                return "Black"
                            }
                        });
                        d3.selectAll(".x-axis").style("fill", function () {
                            if (config["bgFlg"] == 1) {
                                return "White"
                            } else {
                                return "Black"
                            }
                        });
                        d3.selectAll(".colorBarSvg")
                            .attr("width", 135);
                        d3.selectAll(".gradrect")
                            .attr("width", 110)
                            .attr("height", 30)
                            .attr("stroke-width", 0);
                        d3.selectAll(".gradrect").attr("fill", "url(#gradient)");
                        discrete_color = 1;
                    }
                    updateTimepoint(750);
                }

                var bnprofile = 0;

                function showprofile() {
                    if (bnprofile == 1) {
                        prof.attr('stroke-width', 2);
                        bnprofile = 0;
                    }
                    else {
                        prof.attr('stroke-width', 0);
                        bnprofile = 1;
                    }
                }

                showprofile();

                var hideimage;

                if (customImgCount > 0) {
                    hideimage = 0;
                }

                function hide() {
                    if (hideimage == 1) {
                        hideimage = 0;
                        d3.select("#hideimg")
                            .style("background", "#319DBF");
                    } else {
                        hideimage = 1;
                        d3.select("#hideimg")
                            .style("background", "#07465A");
                    }
                    nodeimage
                        .attr("xlink:href", function (d) {
                            if (d.root == "true" && hideimage == 0 && urlExists(workFolder + d.icon)) {
                                d3.selectAll("#nodeShape.focalNodeCircle")
                                    .transition()
                                    .duration(250)
                                    .attrTween("transform", function (d, i, a) {
                                        return d3.interpolateString(a, 'scale(0)');
                                    });
                                return workFolder + d.icon;

                            }
                            else {
                                d3.selectAll("#nodeShape.focalNodeCircle")
                                    .transition()
                                    .duration(250)
                                    .attrTween("transform", function (d, i, a) {
                                        return d3.interpolateString(a, 'scale(1)');
                                    });
                                return "assets/images/transparent.gif";
                            }
                        });
                }

                var nodeimage = node.append("image")
                    .attr("xlink:href", function (d) {
                        if (d.root == "true" && hideimage == 0 && urlExists(workFolder + d.icon)) {
                            return workFolder + d.icon;
                        }
                        else {
                            return "assets/images/transparent.gif";
                        }
                    })
                    .style("opacity", 0.8)
                    .attr("x", function (d) {
                        if (d.root == "true") {
                            return -40;
                        } else {
                            return 0;
                        }
                    })
                    .attr("y", function (d) {
                        if (d.root == "true") {
                            return -40;
                        } else {
                            return 0;
                        }
                    })
                    .attr("width", function (d) {
                        if (d.root == "true" && hideimage == 1 || d.root == "true" && d.icon == "transparent.gif") {
                            return 0;
                        } else if (d.root == "true" && hideimage == 0 && urlExists(workFolder + d.icon)) {
                            return 80;
                        } else {
                            return 0;
                        }
                    })
                    .attr("height", function (d) {
                        if (d.root == "true" && hideimage == 1 || d.root == "true" && d.icon == "transparent.gif") {
                            return 0;
                        } else if (d.root == "true" && hideimage == 0 && urlExists(workFolder + d.icon)) {
                            return 80;
                        } else {
                            return 0;
                        }
                    });

                var shape = ["circle", "square", "diamond", "cross", "triangle-down", "triangle-up"];

                var nodecir = node.append("path")
                    .attr("d", d3.svg.symbol()
                        .size(function (d) {
                            if (d.root == 'false') {
                                return d.size * 20;
                            } else {
                                return 100;
                            }
                        })
                        .type(function (d) {
                            if (d.root == 'false') {
                                return shape[d.shape];
                            }
                        }))
                    .attr("r", function (d) {
                        if (d.root == "true") {
                            if (hideimage == 0 && urlExists(workFolder + d.icon)) {
                                return 0;
                            } else {
                                return 10;
                            }
                        } else {
                            return d.size;
                        }
                    })
                    .attr("id", "nodeShape")
                    .style("stroke", function (d) {
                        if (d.root == "true") {
                            return "orange";
                        } else {
                            return type_hash[d.type];
                        }
                    }) // Make the nodes hollow looking
                    .style("fill", function (d) {
                        if (d.root == "true") {
                            return colorScale_type(+d.type);
                        } else {
                            return colorScale(+d.color);
                        }
                    }) // Make the nodes hollow looking
                    .style("fill-opacity", function (d) {
                        if (d.root == "true") {
                            return 0.5;
                        } else {
                            return 0.9;
                        }
                    })
                    .attr("type_value", function (d) {
                        return d.type;
                    })
                    .attr("color_value", function (d) {
                        if (d.root == "true") {
                            return "#6E92A1";
                        } else {
                            return type_hash[d.type];
                        }
                    })
                    .attr("class", function (d) {
                        if (d.root == "true") {
                            return "focalNodeCircle";
                        }
                        else {
                            var str = d.type;
                            var strippedString = str.replace(/ /g, "_");
                            return "nodeCircle-" + strippedString;
                        }
                    })
                    .style("stroke-width", function (d) {
                        if (d.root == "true") {
                            return 2;
                        } else {
                            return config["BorderWidth"];
                        }
                    }); // Give the node strokes some thickness

                function customImg() {
                    d3.selectAll("#nodeShape.focalNodeCircle")
                        .transition()
                        .duration(250)
                        .attrTween("transform", function (d, i, a) {
                            return d3.interpolateString(a, 'scale(0)');
                        });
                }

                if (customImgCount > 0 && hideimage == 0) {
                    customImg();
                }

                // Append text to Nodes
                var Fontnode = node.append("text")
                    .attr("x", function (d) {
                        if (d.root == "true") {
                            return 0;
                        } else {
                            return -20;
                        }
                    })
                    .attr("y", function (d) {
                        if (d.root == "true") {
                            return 40;
                        } else {
                            return -18;
                        }
                    })
                    .attr("text-anchor", function (d) {
                        if (d.root == "true") {
                            return "middle";
                        } else {
                            return "start";
                        }
                    })
                    .attr("font-family", "Verdana, Helvetica, sans-serif")
                    .style("font", function (d) {
                        if (d.root == "true") {
                            return "bold 14px Verdana";
                        } else {
                            return "normal 11px Verdana";
                        }
                    })
                    .attr("fill", function (d) {
                        if (d.root == "true") {
                            return config["FontColor_center"];
                        } else {
                            return config["FontColor_node"];
                        }
                    })
                    .attr("type_value", function (d, i) {
                        return d.type;
                    })
                    .attr("color_value", function (d, i) {
                        return type_hash[d.type];
                    })
                    .attr("class", function (d, i) {
                        var str = d.type;
                        var strippedString = str.replace(/ /g, "_");
                        if (d.root == "true") {
                            return "focalNodeText";
                        }
                        else {
                            return "nodeText-" + strippedString;
                        }
                    })
                    .attr("dy", ".35em")
                    .text(function (d) {
                        var rootid = d.name;
                        return rootid.substring(0, 30);
                    });

                // Append text to Link edges
                var linkText = svgCanvas.selectAll(".gLink")
                    .data(force.links())
                    .append("text")
                    .attr("font-family", "Verdana, Helvetica, sans-serif")
                    .attr("x", function (d) {
                        if (d.target.x > d.source.x) {
                            return (d.source.x + (d.target.x - d.source.x) / 2);
                        }
                        else {
                            return (d.target.x + (d.source.x - d.target.x) / 2);
                        }
                    })
                    .attr("y", function (d) {
                        if (d.target.y > d.source.y) {
                            return (d.source.y + (d.target.y - d.source.y) / 2);
                        }
                        else {
                            return (d.target.y + (d.source.y - d.target.y) / 2);
                        }
                    })
                    .attr("fill", "Black")
                    .attr("class", "linkText")
                    .style("font", "normal 12px Verdana")
                    .attr("dy", ".35em")
                    .text(function (d) {
                        return d.linkName;
                    });

                // remove duplicate nodes
                var selected = d3.selectAll('g.node')  //select all the nodes
                    .each(function (d) {
                        if (d.weight == 0) {
                            this.remove();
                        }
                    });

                // Collision detection functions
                var padding = 10; // separation between circles
                function collide(alpha) {
                    var quadtree = d3.geom.quadtree(nodeSet);
                    return function (d) {
                        var rb  = 2 * (d.size) + padding,
                            nx1 = d.x - rb,
                            nx2 = d.x + rb,
                            ny1 = d.y - rb,
                            ny2 = d.y + rb;
                        quadtree.visit(function (quad, x1, y1, x2, y2) {
                            if (quad.point && (quad.point !== d)) {
                                var x = d.x - quad.point.x,
                                    y = d.y - quad.point.y,
                                    l = Math.sqrt(x * x + y * y);
                                if (l < rb) {
                                    l = (l - rb) / l * alpha;
                                    d.x -= x *= l;
                                    d.y -= y *= l;
                                    quad.point.x += x;
                                    quad.point.y += y;
                                }
                            }
                            return x1 > nx2 || x2 < nx1 || y1 > ny2 || y2 < ny1;
                        });
                    };
                }

                //Node highlighting functions
                //Toggle stores whether the highlighting is on
                var toggle = 0;
                //Create an array logging what is connected to what
                var linkedByIndex = {};
                for (i = 0; i < nodeSet.length; i++) {
                    linkedByIndex[i + "," + i] = 1;
                }

                linkSet.forEach(function (d) {
                    linkedByIndex[d.source.index + "," + d.target.index] = 1;
                });

                //This function looks up whether a pair are neighbours
                function neighboring(a, b) {
                    return linkedByIndex[a.index + "," + b.index];
                }

                function connectedNodes() {
                    d3.event.stopPropagation();
                    if (toggle == 0) {
                        //Reduce the opacity of all but the neighbouring nodes
                        d = d3.select(this).node().__data__;
                        node.style("opacity", function (o) {
                            return neighboring(d, o) | neighboring(o, d) ? 1 : 0.1;
                        });
                        link.style("opacity", function (o) {
                            return d.index == o.source.index | d.index == o.target.index ? 1 : 0.1;
                        });
                        //Reduce the op
                        toggle = 1;
                    } else {
                        //Put them back to opacity=1
                        node.style("opacity", 1);
                        link.style("opacity", 1);
                        toggle = 0;
                    }
                }

                //Search functionality
                var optArray = [];
                for (var i = 0; i < nodeSet.length - 1; i++) {
                    optArray.push(nodeSet[i].name);
                }
                optArray = optArray.sort();
                $(function () {
                    $("#search").autocomplete({
                        source : optArray
                    });
                });
                var targetX;
                var targetY;

                function searchNode() {
                    //find the node
                    var selectedVal = document.getElementById('search').value;
                    var node = d3.selectAll(".node");
                    if (selectedVal == "" || selectedVal == null) {
                        return false;
                    } else {
                        var selected = node.filter(function (d) {
                            if (d.name == selectedVal) {
                                targetX = d.x;
                                targetY = d.y;
                            }
                            return d.name != selectedVal;
                        });

                        var translate = [1000 / 2 - currentZoom * targetX, 500 / 2 - currentZoom * targetY];
                        svgCanvas.transition().duration(1000)
                            .call(zoom.translate(translate).scale(currentZoom - zoomOffset).event);

                        selected.style("opacity", "0");
                        var link = d3.selectAll(".link");
                        link.style("opacity", "0");
                        d3.selectAll(".node, .link").transition()
                            .duration(5000)
                            .style("opacity", 1);
                        document.getElementById('search').value = "";
                    }
                }

                function tick() {
                    link.attr("x1", function (d) {
                        if ((rootnodes.indexOf(d.targetId) >= 0) && (rootnodes.indexOf(d.sourceId) >= 0)) {
                            return d.source.x;
                        } else if (rootnodes.indexOf(d.targetId) >= 0) {
                            return d.source.x;
                        } else {
                            theta = Math.atan((d.source.y - d.target.y) / (d.source.x - d.target.x));
                            return d.source.x + 15 * Math.cos(theta) * (d.source.x > d.target.x ? -1 : 1);
                        }
                    })
                        .attr("y1", function (d) {
                            if ((rootnodes.indexOf(d.targetId) >= 0) && (rootnodes.indexOf(d.sourceId) >= 0)) {
                                return d.source.y;
                            } else if (rootnodes.indexOf(d.targetId) >= 0) {
                                return d.source.y;
                            } else {
                                theta = Math.atan((d.source.y - d.target.y) / (d.source.x - d.target.x));
                                return d.source.y + 15 * Math.sin(theta) * (d.source.x > d.target.x ? -1 : 1);
                            }
                        })
                        .attr("x2", function (d) {
                            if ((rootnodes.indexOf(d.targetId) >= 0) && (rootnodes.indexOf(d.sourceId) >= 0)) {
                                return d.target.x;
                            } else if (rootnodes.indexOf(d.targetId) >= 0) {
                                return d.target.x;
                            } else {
                                theta = Math.atan((d.source.y - d.target.y) / (d.source.x - d.target.x));
                                return d.target.x - 15 * Math.cos(theta) * (d.source.x > d.target.x ? -1 : 1);
                            }
                        })
                        .attr("y2", function (d) {
                            if ((rootnodes.indexOf(d.targetId) >= 0) && (rootnodes.indexOf(d.sourceId) >= 0)) {
                                return d.target.y;
                            } else if (rootnodes.indexOf(d.targetId) >= 0) {
                                return d.target.y;
                            } else {
                                theta = Math.atan((d.source.y - d.target.y) / (d.source.x - d.target.x));
                                return d.target.y - 15 * Math.sin(theta) * (d.source.x > d.target.x ? -1 : 1);
                            }
                        });
                    node.attr("transform", function (d) {
                        return "translate(" + d.x + "," + d.y + ")";
                    });

                    //node.each(collide(0.003));

                    linkText.attr("x", function (d) {
                        if (d.target.x > d.source.x) {
                            return (d.source.x + (d.target.x - d.source.x) / 2);
                        }
                        else {
                            return (d.target.x + (d.source.x - d.target.x) / 2);
                        }
                    })
                        .attr("y", function (d) {
                            if (d.target.y > d.source.y) {
                                return (d.source.y + (d.target.y - d.source.y) / 2);
                            }
                            else {
                                return (d.target.y + (d.source.y - d.target.y) / 2);
                            }
                        });

                    if (running === true) {
                        if (force.alpha() < 0.01) {
                            force.alpha(0.01);
                        }
                    }
                }

                var zoomcount = 1;

                function zoomin() {
                    zoomcount = zoomcount + 0.1;
                    svgCanvas.attr("transform", "translate(" + currentTranslate + ")scale(" + (currentZoom + 0.1) + ")");
                    zoomOffset += 0.1;
                    currentZoom += 0.1
                }

                function zoomout() {
                    zoomcount = zoomcount - 0.1;
                    svgCanvas.attr("transform", "translate(" + currentTranslate + ")scale(" + (currentZoom - 0.1) + ")");
                    zoomOffset -= 0.1;
                    currentZoom -= 0.1;
                }

                function reset() {
                    zoomcount = 1;
                    svgCanvas.attr("transform", "translate(" + zoom.translate([0, 0]) + ")scale(" + zoom.scale(1) + ")");
                    currentZoom = 1;
                    currentTranslate = [0, 0];
                    zoomOffset = 0;
                }

                function grad() {
                    var gradient = svgCanvas.append("svg:defs")
                        .append("svg:linearGradient")
                        .attr("id", "gradient")
                        .attr("x1", "0%")
                        .attr("y1", "0%")
                        .attr("x2", "100%")
                        .attr("y2", "0%");
                    gradient.append("svg:stop")
                        .attr("offset", "0%")
                        .attr("stop-color", color_hash.min)
                        .attr("stop-opacity", 1);
                    gradient.append("svg:stop")
                        .attr("offset", "25%")
                        .attr("stop-color", color_hash.per25)
                        .attr("stop-opacity", 1);
                    gradient.append("svg:stop")
                        .attr("offset", "50%")
                        .attr("stop-color", color_hash.median)
                        .attr("stop-opacity", 1);
                    gradient.append("svg:stop")
                        .attr("offset", "75%")
                        .attr("stop-color", color_hash.per75)
                        .attr("stop-opacity", 1);
                    gradient.append("svg:stop")
                        .attr("offset", "100%")
                        .attr("stop-color", color_hash.max)
                        .attr("stop-opacity", 1);

                    d3.select("#x1").text(d3.round(colorScale.domain()[0]));
                    d3.select("#x2").text(d3.round(colorScale.domain()[2]));
                    d3.select("#x3").text(d3.round(colorScale.domain()[4]));
                }

                var labelDiv = d3.select("#graph-labels");

                var playLabel = labelDiv.append("p").attr("class", "playLabel")
                    .text("")
                    .style("fill", "Black")
                    .style("font", "bold 16px Verdana");

                var timePointLabel = labelDiv.append("p").attr("class", "time-point")
                    .text('Time point: ' + strtime[count - 1])
                    .style("fill", "Black")
                    .style("font", "bold 16px Verdana");

                if (tpConfigArray.length > 0) {
                    var tpImg = labelDiv.append("img")
                        .attr("class", "tpImg")
                        .attr("src", tpConfigArray[0]['IMAGE'])
                        .attr("width", 200);

                    var tpDesc = labelDiv.append("p")
                        .attr("class", "tpDesc")
                        .text(tpConfigArray[0]['DESCRIPTION'])
                        .style("margin-top", "10px")
                        .style("fill", "Black")
                        .style("font", "12px Verdana");
                }

                var sizeLabel = labelDiv.append("p").attr("class", "sizeLabel")
                    .text(function () {
                        if (config["sizeLabel"] == "" || config["sizeLabel"] == null) {
                            return "Size: ";
                        } else {
                            return config["sizeLabel"];
                        }
                    })
                    .style("fill", "Black")
                    .style("font", "bold 16px Verdana");

                var colorLabel = labelDiv.append("p").attr("class", "colorLabel")
                    .text(function () {
                        if (config["colorLabel"] == "" || config["colorLabel"] == null) {
                            return "Color: ";
                        } else {
                            return config["colorLabel"];
                        }
                    })
                    .style("fill", "Black")
                    .style("font", "bold 16px Verdana");

                labelDiv.append("svg")
                    .attr("width", 135)
                    .attr("height", 60)
                    .attr("class", "colorBarSvg")
                    .append("rect")
                    .attr("class", "gradrect")
                    .attr("x", -1 * (width / 2 - 10))
                    .attr("y", (-height / 7 * 3))
                    .attr("width", 110)
                    .attr("height", 30)
                    .attr("fill", "url(#gradient)");

                var colorBarSvg = d3.select(".colorBarSvg");

                colorBarSvg.append("text")
                    .attr("class", "region")
                    .attr("id", "x1")
                    .text(d3.round(colorScale.domain()[0]))
                    .attr("x", -1 * (width / 2 - 10))
                    .attr("y", (-height / 7 * 3 + 50))
                    .style("fill", function () {
                        if (config["bgFlg"] == 1) {
                            return "White"
                        } else {
                            return "Black"
                        }
                    })
                    .style("font", "bold 16px Verdana")
                    .attr("text-anchor", "start");

                colorBarSvg.append("text")
                    .attr("class", "region")
                    .attr("id", "x2")
                    .text(d3.round(colorScale.domain()[2]))
                    .attr("x", -1 * (width / 2 - 60))
                    .attr("y", (-height / 7 * 3 + 50))
                    .style("fill", function () {
                        if (config["bgFlg"] == 1) {
                            return "White"
                        } else {
                            return "Black"
                        }
                    })
                    .style("font", "bold 16px Verdana")
                    .attr("text-anchor", "start");

                colorBarSvg.append("text")
                    .attr("class", "region")
                    .attr("id", "x3")
                    .text(d3.round(colorScale.domain()[4]))
                    .attr("x", -1 * (width / 2 - 110))
                    .attr("y", (-height / 7 * 3 + 50))
                    .style("fill", function () {
                        if (config["bgFlg"] == 1) {
                            return "White"
                        } else {
                            return "Black"
                        }
                    })
                    .style("font", "bold 16px Verdana")
                    .attr("text-anchor", "start");

                var typeLabel = labelDiv.append("p").attr("class", "typeLabel")
                    .text("Type: ")
                    .text(function () {
                        if (config["typeLabel"] == "" || config["typeLabel"] == null) {
                            return "Type: ";
                        } else {
                            return config["typeLabel"];
                        }
                    })
                    .style("fill", "Black")
                    .style("font", "bold 16px Verdana");

                var legendSvg = labelDiv.append("svg")
                    .attr("class", "legendSvg");

                legendSvg.selectAll("focalNodeCanvas")
                    .data(sortedKeys_type).enter().append("circle") // Append circle elements
                    .attr("cx", -1 * (width / 2) + 15)
                    .attr("cy", function (d, i) {
                        return (i * 20 - height / 7 * 3 + 10);
                    })
                    .style("stroke", function (d, i) {
                        return type_hash[d];
                    })
                    .attr("stroke-width", "5")
                    .style("fill", 'white')
                    .style("fill-opacity", 0.5)
                    .attr("r", 5)
                    .attr("color_value", function (d, i) {
                        return type_hash[d];
                    })
                    .attr("type_value", function (d, i) {
                        return d;
                    })
                    .attr("index_value", function (d, i) {
                        return "index-" + i;
                    })
                    .attr("class", function (d) {
                        var str = d;
                        var strippedString = str.replace(/ /g, "_");
                        return "legendBullet-" + strippedString;
                    });

                legendSvg.selectAll("a.legend_link")
                    .data(sortedKeys_type) // Instruct to bind dataSet to text elements
                    .enter().append("svg:a") // Append legend elements
                    .append("text")
                    .attr("text-anchor", "center")
                    .attr("x", -1 * (width / 2) + 30)
                    .attr("y", function (d, i) {
                        return (i * 20 - height / 7 * 3 + 10);
                    })
                    .attr("dx", 0)
                    .attr("dy", "4px") // Controls padding to place text in alignment with bullets
                    .text(function (d) {
                        return d;
                    })
                    .attr("color_value", function (d, i) {
                        return colorScale(+d);
                    })
                    .attr("type_value", function (d, i) {
                        return d;
                    })
                    .attr("index_value", function (d, i) {
                        return "index-" + i;
                    })
                    .attr("class", function (d) {
                        var str = d;
                        var strippedString = str.replace(/ /g, "_")
                        return "legendText-" + strippedString;
                    })
                    .style("fill", "Black")
                    .style("font", "normal 12px Verdana")
                    .on('mouseover', typeMouseOver)
                    .on("mouseout", typeMouseOut);

                grad();

                var connectFlg = 0;

                function connectGroup() {
                    if (connectFlg == 0) {
                        d3.selectAll("line.rLink")
                            .style("stroke-width", function (d) {
                                return config["Link-width"] * +d.link_scale;
                            })
                            .style("stroke", function (d) {
                                if (d.linkColor == "" || d.linkColor == null) {
                                    return config["Link-color"];
                                } else {
                                    return d.linkColor;
                                }
                            });
                        d3.select("#connectGroup")
                            .style("background", "#07465A");
                        connectFlg = 1;
                    } else {
                        d3.selectAll("line.rLink")
                            .style("stroke-width", 0)
                            .style("stroke", "none");
                        d3.select("#connectGroup")
                            .style("background", "#319DBF");
                        connectFlg = 0;
                    }
                }

                function invertBackground() {
                    if (config["bgFlg"] == 0) {
                        d3.select(".chart")
                            .style("background-color", "Black");
                        d3.select("#graph-labels")
                            .style("background-color", "#202020");
                        d3.selectAll(".linkText")
                            .style("fill", "White");
                        d3.selectAll("#graph-labels text")
                            .style("fill", "#ffffff");
                        d3.selectAll("#graph-labels p")
                            .style("color", "#ffffff");
                        d3.selectAll(".region").style("fill", function () {
                            if (discrete_color == 1) {
                                return "White"
                            } else {
                                return "Black"
                            }
                        });
                        config["FontColor_node"] = "#ffffff";
                        config["FontColor_center"] = "#7575ff";

                        $('[class^="nodeText-"]').css("fill", config["FontColor_node"]);
                        $('[class="focalNodeText"]').css("fill", config["FontColor_center"]);

                        //Fontnode.attr("fill", function(d) { if(d.root=="true") {return config["FontColor_center"];} else {return config["FontColor_node"];} });
                        FontColor_node.updateDisplay();
                        FontColor_center.updateDisplay();
                        d3.select("#invertBackground")
                            .style("background", "#07465A");
                        d3.select("#showBackground")
                            .attr("disabled", "disabled");
                        config["bgFlg"] = 1;
                    } else {
                        d3.selectAll(".chart")
                            .style("background-color", "White");
                        d3.select("#graph-labels")
                            .style("background-color", "#e5e5e5");
                        d3.selectAll(".linkText")
                            .style("fill", "Black");
                        d3.selectAll("#graph-labels text")
                            .style("fill", "#000000");
                        d3.selectAll("#graph-labels p")
                            .style("color", "#000000");
                        d3.select("#showBackground")
                            .attr("disabled", null);
                        d3.selectAll(".region").style("fill", function () {
                            if (discrete_color == 1) {
                                return "Black"
                            } else {
                                return "White"
                            }
                        });
                        config["FontColor_node"] = "#000000";
                        config["FontColor_center"] = "#0000ff";

                        $('[class^="nodeText-"]').css("fill", config["FontColor_node"]);
                        $('[class="focalNodeText"]').css("fill", config["FontColor_center"]);

                        //Fontnode.attr("fill", function(d) { if(d.root=="true") {return config["FontColor_center"];} else {return config["FontColor_node"];} });
                        FontColor_node.updateDisplay();
                        FontColor_center.updateDisplay();
                        d3.select("#invertBackground")
                            .style("background", "#319DBF");
                        config["bgFlg"] = 0;
                    }
                }

                function showBackground() {
                    if (config["background"] == 1) {
                        $(".chart").css("background", "");
                        d3.select("#showBackground").style("background", "#319DBF");
                        d3.select("#invertBackground")
                            .attr("disabled", null);
                        config["background"] = 0;
                    } else {
                        $(".chart").css('background-image', 'url("' + workFolder + 'background.jpg")');
                        d3.select("#showBackground").style("background", "#07465A");
                        d3.select("#invertBackground")
                            .attr("disabled", "disabled");
                        config["background"] = 1;
                    }
                }

                showBackground();
                invertBackground();

                /*
                 One-time initialization
                 */

                function SVG(tag) {
                    return document.createElementNS('http://www.w3.org/2000/svg', tag);
                }

                var DELAY = 250, clicks = 0, timer = null;

                $('.node').longpress(function (e) {
                    // longpress callback
                    // Specify default description field for nodes
                    var description;
                    if (typeof this[0].__data__.description == 'undefined' || this[0].__data__.description == null || this[0].__data__.description.length == 0) {
                        description = "No additional information found for this node.";
                    } else {
                        description = this[0].__data__.description;
                    }

                    $('.pop')
                        .html("<strong>Node: <a href=\"#\">" + this[0].__data__.name + "</a></strong><p>" + description + "</p>")
                        .css({'top' : (mouseY + 20), 'left' : (mouseX - 50)})
                        .slideFadeToggle();
                }, function (e) {
                    clicks++;  //count clicks
                    var thisID = this[0].id;

                    if (clicks === 1) {

                        timer = setTimeout(function () {

                            //alert("Single Click");  //perform single-click action
                            var selectedNodeClass = thisID.replace(/ /g, "_");
                            console.log(thisID);
                            var selectedElement = document.getElementById(thisID);
                            console.log(selectedElement);

                            if (selectedElement.classList.contains("selectedNode")) {
                                selectedElement.classList.remove("selectedNode");
                                $('.selectedNodeCircle-' + thisID.replace(/ /g, "_")).remove();
                            } else {
                                selectedElement.classList.add("selectedNode");
                                $(SVG('circle'))
                                    .attr('cx', 0)
                                    .attr('cy', 0)
                                    .attr('r', 30)
                                    .attr('fill', 'yellow')
                                    .attr('stroke', 'green')
                                    .attr('stroke-width', 3)
                                    .attr('opacity', 0.2)
                                    .attr('class', 'selectedNodeCircle-' + thisID.replace(/ /g, "_"))
                                    .appendTo($("." + selectedNodeClass).children("a"));
                            }
                            clicks = 0;             //after action performed, reset counter

                        }, DELAY);

                    } else {

                        clearTimeout(timer);    //prevent single-click action
                        //alert("Double Click");  //perform double-click action
                        clicks = 0;             //after action performed, reset counter
                    }
                }, 500);

                $(document).ready(function () {
                    $(window).keydown(function (event) {
                        if (event.keyCode == 13) {
                            event.preventDefault();
                            return false;
                        }
                    });
                    // Attached actions to the buttons
                    $("#save_as_png").click(function () {
                        reset();
                        svgCanvas.append("text").attr("class", "tpLabelPrint")
                            .text($(timePointLabel).text())
                            .attr("class", "legendPrint")
                            .attr("x", -1 * (width / 2 - 5))
                            .attr("y", (-height / 7 * 3 + 20))
                            .style("fill", "Black")
                            .style("font", "bold 16px Verdana")
                            .attr("text-anchor", "start");

                        svgCanvas.append("text").attr("class", "sizeLabelPrint")
                            .text($(sizeLabel).text())
                            .attr("class", "legendPrint")
                            .attr("x", -1 * (width / 2 - 5))
                            .attr("y", (-height / 7 * 3 + 50))
                            .style("fill", "Black")
                            .style("font", "bold 16px Verdana")
                            .attr("text-anchor", "start");

                        svgCanvas.append("text").attr("class", "colorLabelPrint")
                            .text($(colorLabel).text())
                            .attr("class", "legendPrint")
                            .attr("x", -1 * (width / 2 - 5))
                            .attr("y", (-height / 7 * 3 + 80))
                            .style("fill", "Black")
                            .style("font", "bold 16px Verdana")
                            .attr("text-anchor", "start");

                        svgCanvas.append("rect")
                            .attr("class", "gradrect legendPrint")
                            .attr("x", -1 * (width / 2 - 15))
                            .attr("y", (-height / 7 * 3 + 95))
                            .attr("width", 110)
                            .attr("height", 30)
                            .attr("fill", "url(#gradient)");

                        svgCanvas.append("text")
                            .attr("class", "region legendPrint")
                            .attr("id", "x1")
                            .text(d3.round(colorScale.domain()[0]))
                            .attr("x", -1 * (width / 2 - 15))
                            .attr("y", (-height / 7 * 3 + 140))
                            .style("fill", "Black")
                            .style("font", "bold 16px Verdana")
                            .attr("text-anchor", "start");

                        svgCanvas.append("text")
                            .attr("class", "region legendPrint")
                            .attr("id", "x2")
                            .text(d3.round(colorScale.domain()[2]))
                            .attr("x", -1 * (width / 2 - 60))
                            .attr("y", (-height / 7 * 3 + 140))
                            .style("fill", "Black")
                            .style("font", "bold 16px Verdana")
                            .attr("text-anchor", "start");

                        svgCanvas.append("text")
                            .attr("class", "region legendPrint")
                            .attr("id", "x3")
                            .text(d3.round(colorScale.domain()[4]))
                            .attr("x", -1 * (width / 2 - 105))
                            .attr("y", (-height / 7 * 3 + 140))
                            .style("fill", "Black")
                            .style("font", "bold 16px Verdana")
                            .attr("text-anchor", "start");

                        svgCanvas.append("text").attr("class", "typeLabelPrint")
                            .text($(typeLabel).text())
                            .attr("class", "legendPrint")
                            .attr("x", -1 * (width / 2 - 5))
                            .attr("y", (-height / 7 * 3 + 167))
                            .style("fill", "Black")
                            .style("font", "bold 16px Verdana")
                            .attr("text-anchor", "start");

                        svgCanvas.selectAll("focalNodeCanvas")
                            .data(sortedKeys_type).enter().append("circle") // Append circle elements
                            .attr("id", "legendPrint")
                            .attr("cx", -1 * (width / 2) + 18)
                            .attr("cy", function (d, i) {
                                return (i * 20 - height / 7 * 3 + 190);
                            })
                            .style("stroke", function (d, i) {
                                return type_hash[d];
                            })
                            .attr("stroke-width", "5")
                            .style("fill", 'white')
                            .style("fill-opacity", 0.5)
                            .attr("r", 5)
                            .attr("color_value", function (d, i) {
                                return type_hash[d];
                            })
                            .attr("type_value", function (d, i) {
                                return d;
                            })
                            .attr("index_value", function (d, i) {
                                return "index-" + i;
                            })
                            .attr("class", function (d) {
                                var str = d;
                                var strippedString = str.replace(/ /g, "_");
                                return "legendBullet-" + strippedString + " legendPrint";
                            });

                        svgCanvas.selectAll("a.legend_link")
                            .data(sortedKeys_type) // Instruct to bind dataSet to text elements
                            .enter().append("svg:a") // Append legend elements
                            .append("text")
                            .attr("text-anchor", "center")
                            .attr("x", -1 * (width / 2) + 35)
                            .attr("y", function (d, i) {
                                return (i * 20 - height / 7 * 3 + 190);
                            })
                            .attr("dx", 0)
                            .attr("dy", "4px") // Controls padding to place text in alignment with bullets
                            .text(function (d) {
                                return d;
                            })
                            .attr("color_value", function (d, i) {
                                return colorScale(+d);
                            })
                            .attr("type_value", function (d, i) {
                                return d;
                            })
                            .attr("index_value", function (d, i) {
                                return "index-" + i;
                            })
                            .attr("class", function (d) {
                                var str = d;
                                var strippedString = str.replace(/ /g, "_")
                                return "legendText-" + strippedString + " legendPrint";
                            })
                            .style("fill", "Black")
                            .style("font", "normal 12px Verdana");
                        var maxX = 0;
                        var maxY = 0;
                        nodeSet.forEach(function (d) {
                            if (d.x > maxX) {
                                maxX = d.x;
                            }
                            if (d.y > maxY) {
                                maxY = d.y;
                            }
                            console.log("MaxX: " + maxX + " | MaxY: " + maxY);
                        });
                        saveSvgAsPng(maxX, maxY, document.getElementById("mysvg"), "moe-export.png", {scale : 1.0});
                        setTimeout(function () {
                            d3.selectAll(".legendPrint").remove();
                        }, 2000);
                    });
                    $("#searchButton").click(function () {
                        searchNode();
                    });
                    $("#animate").click(function () {
                        if (running === true) {
                            running = false;
                            clearInterval(animate);
                            $("span.glyphicon-pause").removeClass("glyphicon-pause").addClass("glyphicon-play");
                            $("#animate").attr("title", "Resume Animation");
                            d3.select(".playLabel").text("Animation Paused...");
                        } else {
                            force.resume(0.001);
                            running = true;
                            animate = setInterval(update, 3500 / Math.sqrt(config["Speed"]));
                            $("span.glyphicon-play").removeClass("glyphicon-play").addClass("glyphicon-pause");
                            $("#animate").attr("title", "Pause Animation");
                            d3.select("#animate")
                                .style("background", "#07465A");
                            d3.select(".playLabel").text("Playing Animation...");
                        }
                    });
                    $("#stopAnimate").click(function () {
                        running = false;
                        clearInterval(animate);
                        d3.select("#animate")
                            .style("background", "#319DBF");
                        d3.select(".playLabel").text("");
                        $("span.glyphicon-pause").removeClass("glyphicon-pause").addClass("glyphicon-play");
                        d3.selectAll(".node").attr("opacity", 0.9);
                    });
                    $("#zoomin").click(function () {
                        zoomin();
                    });
                    $("#zoomout").click(function () {
                        zoomout();
                    });
                    $("#reset").click(function () {
                        reset();
                    });
                    $("#profile").click(function () {
                        showprofile();
                    });
                    var legend = 1;
                    $("#toggleLegend").click(function () {
                        if (legend == 1) {
                            $("#graph-labels").hide();
                            d3.select("#toggleLegend").style("background", "#07465A");
                            legend = 0;
                        } else {
                            $("#graph-labels").show();
                            d3.select("#toggleLegend").style("background", "#319DBF");
                            legend = 1;
                        }
                    });
                    $("#invertBackground").click(function () {
                        invertBackground();
                    });
                    $("#showBackground").click(function () {
                        showBackground();
                    });
                    $("#connectGroup").click(function () {
                        connectGroup();
                    });
                    $("#hideimg").click(function () {
                        hide();
                    });
                    $("#discrete").click(function () {
                        discrete();
                    });
                    $("#colorLabel").keyup(function () {
                        var id = "colorLabel";
                        catchValue(id);
                    });
                    $("#sizeLabel").keyup(function () {
                        var id = "sizeLabel";
                        catchValue(id);
                    });
                    $("#typeLabel").keyup(function () {
                        var id = "typeLabel";
                        catchValue(id);
                    });
                    $("#save_config").click(function () {
                        saveConfig();
                    });
                    $("#saveLayoutOK").click(function () {
                        location.reload();
                    });
                    $("#resetLayout").click(function () {
                        resetConfig();
                    });
                    $("#download").click(function () {
                        saveConfig();
                        setTimeout(function () {
                            window.location = "includes/download.php?workFolder=" + workFolder;
                        }, 1000);
                    });
                    $("#updateLabelsButton").click(function () {
                        updateLabels();
                    });
                    $('[data-tooltip="tooltip"]').tooltip({'placement' : 'top'});
                });

                $.fn.slideFadeToggle = function (easing, callback) {
                    return this.animate({opacity : 'toggle', height : 'toggle'}, 'fast', easing, callback);
                };

                var updateTimepoint = function () {
                    //update node parameters
                    var node = d3.selectAll('g.node')  //select all the nodes
                        .each(function (d) {
                            d3.select(this)
                                .attr("type_value", function (d) {
                                    return d.type;
                                })
                                .attr("color_value", function (d) {
                                    return type_hash[d.type];
                                });

                            d3.select(this).select("#nodeShape").transition()
                                .duration(750)
                                .style("fill", function (d) {
                                    if (d.root == "true") {
                                        return colorScale_type(+d.type);
                                    }
                                    else if (discrete_color == 1) {
                                        return colorScale(+d.color);
                                    } else {
                                        return color_hash_dis[+d.color];
                                    }
                                })
                                .style("stroke", function (d) {
                                    if (d.root == "true") {
                                        return "orange";
                                    }
                                    else {
                                        return type_hash[d.type];
                                    }
                                })
                                .style("fill-opacity", function (d) {
                                    if (d.root == "true") {
                                        return 0.5;
                                    } else {
                                        return 0.9;
                                    }
                                })
                                .attr("type_value", function (d) {
                                    return d.type;
                                })
                                .attr("color_value", function (d) {
                                    if (d.root == "true") {
                                        return "#6E92A1";
                                    }
                                    else {
                                        return type_hash[d.type];
                                    }
                                })
                                .attr("class", function (d) {
                                    var str = d.type;
                                    var strippedString = str.replace(/ /g, "_");
                                    if (d.root == "true") {
                                        return "focalNodeCircle";
                                    } else {
                                        return "nodeCircle-" + strippedString;
                                    }
                                })
                                .style("stroke-width", function (d) {
                                    if (d.root == "true") {
                                        return 2;
                                    } else {
                                        return config["BorderWidth"];
                                    }
                                }); // Give the node strokes some thickness
                        });

                    // update Links between Nodes
                    var link = svgCanvas.selectAll(".gLink")
                        .data(force.links())
                        .enter().append("g")
                        .attr("class", "gLink")
                        .append("line")
                        .attr("class", function (d) {
                            var idx = rootnodes.indexOf(d.targetId);
                            if (idx >= 0) {
                                return "link rLink";
                            } else return "link nLink";
                        })
                        .style("stroke-width", function (d) {
                            var idx = rootnodes.indexOf(d.targetId);
                            if (idx >= 0) {
                                return 0;
                            } else return 1.5;
                        })
                        .style("stroke", function (d) {
                            var idx = rootnodes.indexOf(d.targetId);
                            if (idx >= 0) {
                                return "none";
                            } else {
                                if (d.linkColor == "" || d.linkColor == null) {
                                    return config["Link-color"];
                                } else {
                                    return d.linkColor;
                                }
                            }
                        });
                };

                function storeConfigValues() {
                    var configData = new Array();
                    var row = 0;
                    var typeColors = [];
                    for (var key in type_hash) {
                        if (type_hash.hasOwnProperty(key)) {
                            //console.log(key + " -> " + type_hash[key]);
                            typeColors.push(type_hash[key]);
                        }
                    }
                    var fillColors = [];
                    for (var key in color_hash) {
                        if (color_hash.hasOwnProperty(key)) {
                            //console.log(key + " -> " + color_hash[key]);
                            fillColors.push(color_hash[key]);
                        }
                    }
                    var discreteFillColors = [];
                    for (var key in color_hash_dis) {
                        if (color_hash_dis.hasOwnProperty(key)) {
                            //console.log(key + " -> " + color_hash_dis[key]);
                            discreteFillColors.push(color_hash_dis[key]);
                        }
                    }
                    //console.log(discreteFillColors);
                    nodeSet.forEach(function (d) {
                        if (d.root == "true") {
                            if (row == 0) {
                                configData[row] = {
                                    "rootId"             : d.id,
                                    "rootX"              : d.x,
                                    "rootY"              : d.y,
                                    "Timepoint"          : 1,
                                    "charge"             : 0,
                                    "gravity"            : 0,
                                    "BorderWidth"        : config["BorderWidth"],
                                    "Link-width"         : config["Link-width"],
                                    "Link-color"         : config["Link-color"],
                                    "max_score"          : config["max_score"],
                                    "min_score"          : config["min_score"],
                                    "Radius"             : config["Radius"],
                                    "FontColor_node"     : config["FontColor_node"],
                                    "FontColor_center"   : config["FontColor_center"],
                                    "saved"              : "yes",
                                    "key"                : currKey,
                                    "colorLabel"         : $("#colorLabel").val(),
                                    "sizeLabel"          : $("#sizeLabel").val(),
                                    "typeLabel"          : $("#typeLabel").val(),
                                    "tpLabels"           : strtime.join(),
                                    "typeColors"         : typeColors.join(),
                                    "fillColors"         : fillColors.join(),
                                    "discreteFillColors" : discreteFillColors.join(),
                                    "bgFlg"              : config["bgFlg"],
                                    "background"         : config["background"]
                                };
                                console.log("File saved!");
                                console.log("config['BorderWidth'] = " + config["BorderWidth"]);
                                console.log("configData['BorderWidth'] = " + configData["BorderWidth"]);
                            } else {
                                configData[row] = {
                                    "rootId" : d.id,
                                    "rootX"  : d.x,
                                    "rootY"  : d.y
                                }
                            }
                            row++;
                        }
                    });
                    return configData;
                }

                function saveConfig() {
                    var workFolder = document.getElementById("workFolder").value;
                    var configData;
                    configData = $.toJSON(storeConfigValues());

                    document.getElementById("secret").innerHTML = '<div class="alert alert-success"><strong>Success.</strong> Your configuration has been saved.<br />Please save your authorization key: ' + currKey + '</div>';

                    var urlString = window.location.href;

                    if (urlString.indexOf("auth") == -1) {
                        var stateObj = {auth : "yes"};
                        history.pushState(stateObj, "Module Explorer (MOE) - Saved Configuration", urlString + "&auth=" + currKey);
                    }

                    $.ajax({
                        type    : "POST",
                        url     : "includes/saveConfig.php",
                        data    : {
                            'workFolder'  : workFolder,
                            'pconfigData' : configData,
                            'externalApp' : externalApp
                        },
                        success : function (msg) {
                            // return value stored in msg variable
                        }
                    });
                    console.log("config['bgFlg'] =" + config["bgFlg"]);
                }

                function resetConfig() {
                    var workFolder = document.getElementById("workFolder").value;

                    var stateObj = {auth : "no"};
                    urlString = window.location.href;
                    var newUrl = urlString.indexOf('&auth');
                    urlString = urlString.substring(0, newUrl != -1 ? newUrl : urlString.length);
                    history.pushState(stateObj, "Module Explorer (MOE) - Reset Configuration", urlString);

                    $.ajax({
                        type    : "POST",
                        url     : "includes/reset.php",
                        data    : {
                            "workFolder" : workFolder
                        },
                        success : function (msg) {
                            location.reload();
                        }
                    });
                }

                var urlString = window.location.href;
                if (urlString.indexOf("auth") == -1) {
                    var stateObj = {auth : "yes"};
                    document.getElementById("unlockedURL").innerHTML = urlString + "&auth=" + currKey;
                } else {
                    document.getElementById("unlockedURL").innerHTML = urlString;
                }

                var lockedUrlString = window.location.href;
                lockedUrlString = lockedUrlString.split("&auth")[0];
                document.getElementById("lockedURL").innerHTML = lockedUrlString;

                function catchValue(id) {
                    var inputValue = $("#" + id).val();
                    d3.select("." + id)
                        .text(inputValue);
                }

                function updateLabels() {
                    for (i = 1; i <= timePointNum; i++) {
                        var labelValue = $("#timepoint" + i).val();
                        if (labelValue == "" || labelValue == null) {
                            strtime[i - 1] = "Time-Point " + i;
                        } else {
                            strtime[i - 1] = labelValue;
                        }
                    }
                }

            });

        });
    });
}
