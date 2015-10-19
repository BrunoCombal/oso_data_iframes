/*
  availableTags: array used with jQuery search boxes, for LMEs charts comparison, and search box above the global map
*/
var availableTags=[
    "01 East Bering Sea",
    "02 Gulf of Alaska",
    "03 California Current",
    "04 Gulf of California",
    "05 Gulf of Mexico",
    "06 Southeast U.S. Continental Shelf",
    "07 Northeast U.S. Continental Shelf",
    "08 Scotian Shelf",
    "09 Newfoundland-Labrador Shelf",
    "10 Insular Pacific-Hawaiian",
    "11 Pacific Central-American Coastal",
    "12 Caribbean Sea",
    "13 Humboldt Current",
    "14 Patagonian Shelf",
    "15 South Brazil Shelf",
    "16 East Brazil Shelf",
    "17 North Brazil Shelf",
    "18 Canadian Eastern Arctic - West Greenland",
    "19 Greenland Sea",
    "20 Barents Sea",
    "21 Norwegian Sea",
    "22 North Sea","23 Baltic Sea",
    "24 Celtic-Biscay Shelf",
    "25 Iberian Coastal",
    "26 Mediterranean Sea",
    "27 Canary Current",
    "28 Guinea Current",
    "29 Benguela Current",
    "30 Agulhas Current",
    "31 Somali Coastal Current",
    "32 Arabian Sea",
    "33 Red Sea",
    "34 Bay of Bengal",
    "35 Gulf of Thailand",
    "36 South China Sea",
    "37 Sulu-Celebes Sea",
    "38 Indonesian Sea",
    "39 North Australian Shelf",
    "40 Northeast Australian Shelf",
    "41 East-Central Australian Shelf",
    "42 Southeast Australian Shelf",
    "43 Southwest Australian Shelf",
    "44 West-Central Australian Shelf",
    "45 Northwest Australian Shelf",
    "46 New Zealand Shelf",
    "47 East China Sea",
    "48 Yellow Sea",
    "49 Kuroshio Current",
    "50 Sea of Japan",
    "51 Oyashio Current",
    "52 Sea of Okhotsk",
    "53 West Bering Sea",
    "54 Chukchi Sea",
    "55 Beaufort Sea",
    "56 East Siberian Sea",
    "57 Laptev Sea",
    "58 Kara Sea",
    "59 Iceland Shelf and Sea",
    "60 Faroe Plateau",
    "61 Antarctica",
    "62 Black Sea",
    "63 Hudson Bay",
    "64 Central Arctic",
    "65 Aleutian Islands",
    "66 Canadian High Arctic / North Greenland",
    "99 Western Pacific Warm Pool"
];
var comboText = "Type LME code or name";
var maxComboText = 'Maximum number of datasets reached';
var nodeCodes={"1":"51", "2":"55", "3":"56", "4":"57", "5":"58", "6":"59", "7":"60", "8":"61", "9":"62", "10":"63",
               "11":"65", "12":"66", "13":"67", "14":"68", "15":"69", "16":"70", "17":"71", "18":"72", "19":"73", "20":"74",
               "21":"75", "22":"76", "23":"77", "24":"78", "25":"79", "26":"80", "27":"81", "28":"82", "29":"83", "30":"84",
               "31":"85", "32":"86", "33":"87", "34":"88", "35":"89", "36":"90", "37":"91", "38":"92", "39":"93", "40":"94",
               "41":"95", "42":"96", "43":"97", "44":"98", "45":"99", "46":"100", "47":"101", "48":"102", "49":"103", "50":"104",
               "51":"105", "52":"106", "53":"107", "54":"108", "55":"109", "56":"110", "57":"111", "58":"112", "59":"113", "60":"114", "61":"115", "62":"116", "63":"117", "64":"118", "65":"119", "66":"120", "99":"242"}; // LME_CODE:"nodeCode"

/*
  var lmeAliasList
  stores URL aliases for LME pages. To be used by the "choose an LME" button.
*/
var lmeAliasList = ["LME_01_East_Bering_Sea","LME_02_Gulf_of_Alaska","LME_03_California_Current","LME_04_Gulf_of_California","LME_05_Gulf_of_Mexico","LME_06_Southeast_US_Continental_Shelf","LME_07_Northeast_US_Continental_Shelf","LME_08_Scotian_Shelf","LME_09_Newfoundland-Labrador_Shelf","LME_10_Insular_Pacific-Hawaiian","LME_11_Pacific_Central-American_Coastal","LME_12_Caribbean_Sea","LME_13_Humboldt_Current","LME_14_Patagonian_Shelf","LME_15_South_Brazil_Shelf","LME_16_East_Brazil_Shelf","LME_17_North_Brazil_Shelf","LME_18_Canadian_Eastern_Arctic_-_West_Greenland","LME_19_Greenland_Sea","LME_20_Barents_Sea","LME_21_Norwegian_Sea","LME_22_North_Sea","LME_23_Baltic_Sea","LME_24_Celtic-Biscay_Shelf","LME_25_Iberian_Coastal","LME_26_Mediterranean_Sea","LME_27_Canary_Current","LME_28_Guinea_Current","LME_29_Benguela_Current","LME_30_Agulhas_Current","LME_31_Somali_Coastal_Current","LME_32_Arabian_Sea","LME_33_Red_Sea","LME_34_Bay_of_Bengal","LME_35_Gulf_of_Thailand","LME_36_South_China_Sea","LME_37_Sulu-Celebes_Sea","LME_38_Indonesian_Sea","LME_39_North_Australian_Shelf","LME_40_Northeast_Australian_Shelf","LME_41_East-Central_Australian_Shelf","LME_42_Southeast_Australian_Shelf","LME_43_Southwest_Australian_Shelf","LME_44_West-Central_Australian_Shelf","LME_45_Northwest_Australian_Shelf","LME_46_New-Zealand_Shelf","LME_47_East_China_Sea","LME_48_Yellow_Sea","LME_49_Kuroshio_Current","LME_50_Sea_of_Japan","LME_51_Oyashio_Current","LME_52_Sea_of_Okhotsk","LME_53_West_Bering_Sea","LME_54_Chukchi_Sea","LME_55_Beaufort_Sea","LME_56_East_Siberian_Sea","LME_57_Laptev_Sea","LME_58_Kara_Sea","LME_59_Iceland_Shelf_and_Sea","LME_60_Faroe_Plateau","LME_61_Antarctica","LME_62_Black_Sea","LME_63_Hudson_Bay","LME_64_Central_Arctic","LME_65_Aleutian_Islands","LME_66_Canadian_High_Arctic_-_North_Greenland"];

function lmeCodeToNode(code){
    node=8; // page not found
    node=nodeCodes[parseInt(code)];
    return node;
};

/*
  var lineColors: list of colors in LMEs charts
*/
var lineColors = ['#2bba1e', '#dc8e24', '#7b1eb7', '#dcd824','#1e64b7', '#d8232a'];

/*
  numberWithCommas: put thousand separators as a comma ','
*/
function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

/*
  Quickfix for Internet Explorer (old version?): add indexOf method to object Array
*/
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(obj, start) {
        for (var i = (start || 0), j = this.length; i < j; i++) {
            if (this[i] === obj) { return i; }
        }
        return -1;
    }
}


/* function copyToClipboard
   used when in a cross-domain iFrame, for UNEP using the iframes.
*/
function copyToClipboard(text) {
    window.open(text, '_blank');
    //window.prompt("To see the data source, please copy the link below to the clipboard\n\n Ctrl+C (Windows) or Cmd+C (Mac), Enter\n\nand paste it to your browser's address bar.", text);
}