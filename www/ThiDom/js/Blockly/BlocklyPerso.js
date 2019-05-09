 /*
Blockly.Blocks.controls_if = {
  category: null,  // Variables are handled specially.
  init: function() {
    this.setColour(120);
    this.appendValueInput('IF0')
        .appendField(Blockly.Msg.CONTROLS_IF_MSG_IF);
    this.appendStatementInput('DO0')
        .appendField(Blockly.Msg.CONTROLS_IF_MSG_THEN);
    this.setTooltip(Blockly.Msg.CONTROLS_IF_TOOLTIP_1);
    //this.setPreviousStatement(true);
    //this.setNextStatement(true);
  }
 };
 
Blockly.Blocks.controls_ifelseif = {
  // If/elseif/else condition.
  helpUrl: Blockly.LANG_CONTROLS_IF_HELPURL,
  init: function() {
    this.setColour(120);
    this.appendValueInput('IF0')
        .appendField(Blockly.Msg.CONTROLS_IF_MSG_IF);
    this.appendStatementInput('DO0')
        .appendField(Blockly.Msg.CONTROLS_IF_MSG_THEN);
    this.setMutator(new Blockly.Mutator(['controls_if_elseif']));
    // Assign 'this' to a variable for use in the tooltip closure below.
    this.setTooltip(Blockly.Msg.CONTROLS_IF_TOOLTIP_1);
    this.elseifCount_ = 0;
    this.elseCount_ = 0;
  },
  mutationToDom: function() {
    if (!this.elseifCount_) {
      return null;
    }
    var container = document.createElement('mutation');
    if (this.elseifCount_) {
      container.setAttribute('elseif', this.elseifCount_);
    }
    return container;
  },
  domToMutation: function(xmlElement) {
    this.elseifCount_ = window.parseInt(xmlElement.getAttribute('elseif'), 10);
    for (var x = 1; x <= this.elseifCount_; x++) {
      this.appendValueInput('IF' + x)
          .appendField(Blockly.Msg.CONTROLS_IF_MSG_ELSEIF);
      this.appendStatementInput('DO' + x)
          .appendField(Blockly.Msg.CONTROLS_IF_MSG_THEN);
    }
  },
  decompose: function(workspace) {
    var containerBlock = new Blockly.Block(workspace, 'controls_if_if');
    containerBlock.initSvg();
    var connection = containerBlock.getInput('STACK').connection;
    for (var x = 1; x <= this.elseifCount_; x++) {
      var elseifBlock = new Blockly.Block(workspace, 'controls_if_elseif');
      elseifBlock.initSvg();
      connection.connect(elseifBlock.previousConnection);
      connection = elseifBlock.nextConnection;
    }
    return containerBlock;
  },
  compose: function(containerBlock) {
    // Disconnect all the elseif input blocks and remove the inputs.
    for (var x = this.elseifCount_; x > 0; x--) {
      this.removeInput('IF' + x);
      this.removeInput('DO' + x);
    }
    this.elseifCount_ = 0;
    // Rebuild the block's optional inputs.
    var clauseBlock = containerBlock.getInputTargetBlock('STACK');
    while (clauseBlock) {
      switch (clauseBlock.type) {
        case 'controls_if_elseif':
          this.elseifCount_++;
          var ifInput = this.appendValueInput('IF' + this.elseifCount_)
              .appendField(Blockly.Msg.CONTROLS_IF_MSG_ELSEIF);
          var doInput = this.appendStatementInput('DO' + this.elseifCount_);
          doInput.appendField(Blockly.Msg.CONTROLS_IF_MSG_IF);
          // Reconnect any child blocks.
          if (clauseBlock.valueConnection_) {
            ifInput.connection.connect(clauseBlock.valueConnection_);
          }
          if (clauseBlock.statementConnection_) {
            doInput.connection.connect(clauseBlock.statementConnection_);
          }
          break;
        default:
          throw 'Unknown block type.';
      }
      clauseBlock = clauseBlock.nextConnection &&
          clauseBlock.nextConnection.targetBlock();
    }
  },
  saveConnections: function(containerBlock) {
    // Store a pointer to any connected child blocks.
    var clauseBlock = containerBlock.getInputTargetBlock('STACK');
    var x = 1;
    while (clauseBlock) {
      switch (clauseBlock.type) {
        case 'controls_if_elseif':
          var inputIf = this.getInput('IF' + x);
          var inputDo = this.getInput('DO' + x);
          clauseBlock.valueConnection_ =
              inputIf && inputIf.connection.targetConnection;
          clauseBlock.statementConnection_ =
              inputDo && inputDo.connection.targetConnection;
          x++;
          break;
        default:
          throw 'Unknown block type.';
      }
      clauseBlock = clauseBlock.nextConnection &&
          clauseBlock.nextConnection.targetBlock();
    }
  }
};
 
*/


Blockly.Blocks.controlsCalling = {
  init: function() {
    this.setColour(210);
    this.setNextStatement(true);
    this.appendDummyInput()
      .appendField("Interaction Calling")
      .appendField(new Blockly.FieldTextInput(''), 'TEXT');
    this.appendStatementInput('DO0')
        .appendField(Blockly.Msg.CONTROLS_IF_MSG_THEN);
    this.setOutput(true, 'String');
    this.setInputsInline(true);  
  }
}; 

Blockly.Blocks.logic_states = {
    init: function() {
        var a = [
            [Blockly.Msg.logic_states_ON, "On"],
            [Blockly.Msg.logic_states_OFF, "Off"],
            [Blockly.Msg.logic_states_Open, "Open"],
            [Blockly.Msg.logic_states_Close, "Close"]
        ];
        this.setHelpUrl(Blockly.Msg.LOGIC_BOOLEAN_HELPURL);
        this.setColour(210);
        this.setOutput(!0, "Boolean");
        this.appendDummyInput().appendField(new Blockly.FieldDropdown(a), "State");
        this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP)
    }
};

Blockly.Blocks.logic_set = {
  // Comparison operator.
  init: function() {
    this.setColour(120);
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.appendValueInput('A')
    .appendField(Blockly.Msg.logic_set);
    this.appendValueInput('B')
    .appendField("=");
    this.setInputsInline(true);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);

  }
};


Blockly.Blocks.logic_setlevel = {
  init: function() {
    this.setColour(120);
    this.appendDummyInput()
    .appendField(Blockly.Msg.logic_setlevel);
    this.appendDummyInput()
    .appendField(new Blockly.FieldTextInput('0',
      this.percentageValidator), 'NUM');
    this.setOutput(true, 'Number');
    this.setInputsInline(true);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};

Blockly.Blocks.logic_setdelayed = {
  // Comparison operator.
  init: function() {
    this.setColour(120);
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.appendValueInput('A')
    .appendField(Blockly.Msg.logic_set);
    this.appendValueInput('B')
    .appendField("=");
    this.appendValueInput('C')
    .appendField(Blockly.Msg.For );
    this.appendDummyInput()
    .appendField(Blockly.Msg.Minutes);
    this.setInputsInline(true);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};

Blockly.Blocks.logic_Execute={
  init: function() {
    this.setColour(120);
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.appendValueInput('A')
    .appendField(Blockly.Msg.logic_Execute);
    this.setInputsInline(true);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);

  }
};

Blockly.Blocks.logic_timeofday = {
  // Comparison operator.
  init: function() {
    this.setColour(120);
    this.setOutput(true, null);
    this.appendValueInput(Blockly.Msg.Time)
    .appendField("Time:")
    .appendField(new Blockly.FieldDropdown(this.OPERATORS), 'OP');
    this.setInputsInline(true);
    var thisBlock = this;
    this.setTooltip(function() {
      var op = thisBlock.getTitleValue('OP');
      return thisBlock.TOOLTIPS[op];
    });
  }
}; 

Blockly.Blocks.logic_weekday = {
  // Variable getter.
  init: function() {
    this.setColour(120);
    this.setOutput(true, null);
    this.appendDummyInput()
    .appendField(Blockly.Msg.Days)
    .appendField(new Blockly.FieldDropdown(this.OPERATORS), 'OP')
    .appendField(" ")
    .appendField(new Blockly.FieldDropdown(this.DAYS), 'Weekday');
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};   

Blockly.Blocks.logic_timevalue = {
  init: function() {
    this.setColour(230);
    this.appendDummyInput()
    .appendField(new Blockly.FieldTextInput('00:00:00',
      this.TimeValidator), 'TEXT');
    this.setOutput(true, 'String');
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};

Blockly.Blocks.logic_timevalue.TimeValidator = function(text) {
  if (text.match(/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]/)) 
  {
    return text;
  }
  else
  {
    return "00:00:00";
  }
};


Blockly.Blocks.logic_sunrisesunset = {
  init: function() {
    this.setOutput(true, null);
    this.setColour(230);
    this.appendDummyInput()
    .appendField(new Blockly.FieldDropdown(this.VALUES), 'SunriseSunset')
    .appendField(" ");
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};


Blockly.Blocks.send_notification = {
  // Comparison operator.
  init: function() {
    this.setColour(120);
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.appendValueInput('notificationTextSubject')
    .appendField("Send notification with subject:");
    this.appendValueInput('notificationTextBody')
    .appendField("and message:");
    this.appendValueInput('notificationTo')
    .appendField("to:");
    this.setInputsInline(true);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};

Blockly.Blocks.send_email = {
  // Comparison operator.
  init: function() {
    this.setColour(120);
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.appendDummyInput()
    .appendField("Send email with subject:")
    .appendField(new Blockly.FieldTextInput(''), 'TextSubject');
    this.appendDummyInput()
    .appendField("and message:")
    .appendField(new Blockly.FieldTextInput(''), 'TextBody');
    this.appendDummyInput()
    .appendField("to:")
    .appendField(new Blockly.FieldTextInput(''), 'TextTo');
    this.setInputsInline(true);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};


var temperatures = [];
var humidity = [];
var barometer = [];
var weather = [];
var utilities = [];
var scenes = [];
var groups = [];



Blockly.Blocks.switchvariablesAF = {
  // Variable getter.
  category: null,  // Variables are handled specially.
  init: function() {
    this.setColour(30);
    this.appendDummyInput()
    .appendField('A-F ')    
    .appendField(new Blockly.FieldDropdown(switchesAF), 'Switch');
    this.setOutput(true, null);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};

Blockly.Blocks.switchvariablesGL = {
  // Variable getter.
  category: null,  // Variables are handled specially.
  init: function() {
    this.setColour(30);
    this.appendDummyInput()
    .appendField('G-L ')
    .appendField(new Blockly.FieldDropdown(switchesGL), 'Switch');
    this.setOutput(true, null);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};

Blockly.Blocks.switchvariablesMR = {
  // Variable getter.
  category: null,  // Variables are handled specially.
  init: function() {
    this.setColour(30);
    this.appendDummyInput()
    .appendField('M-R ')
    .appendField(new Blockly.FieldDropdown(switchesMR), 'Switch');
    this.setOutput(true, null);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};

Blockly.Blocks.switchvariablesSZ = {
  // Variable getter.
  category: null,  // Variables are handled specially.
  init: function() {
    this.setColour(30);
    this.appendDummyInput()
    .appendField('S-Z ')
    .appendField(new Blockly.FieldDropdown(switchesSZ), 'Switch');
    this.setOutput(true, null);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};

/*
$.ajax({
  url: "json.htm?type=devices&filter=temp&used=true&order=Name", 
  async: false, 
  dataType: 'json',
  success: function(data) {
    if (typeof data.result != 'undefined') {
      $.each(data.result, function(i,item){
        if (item.Type.toLowerCase().indexOf("temp") >= 0) {
          temperatures.push([item.Name,item.ID])
        }
        if ((item.Type == "RFXSensor") && (item.SubType == "Temperature")) {
          temperatures.push([item.Name,item.ID])
        }
        if (item.Type.toLowerCase().indexOf("hum") >= 0) {
          humidity.push([item.Name,item.idx])
        }
        if (item.Type.toLowerCase().indexOf("baro") >= 0) {
          barometer.push([item.Name,item.idx])
        }
      })
    }
  }
});
*/


if (temperatures.length === 0) {temperatures.push(["No temperatures found",0]);}
temperatures.sort();

Blockly.Blocks.temperaturevariables = {
  // Variable getter.
  category: null,  // Variables are handled specially.
  init: function() {
    this.setColour(330);
    this.appendDummyInput()
    .appendField(Blockly.Msg.Temp,'TemperatureLabel')
    .appendField(new Blockly.FieldDropdown(temperatures), 'Temperature');
    this.setInputsInline(true);
    this.setOutput(true, null);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};


Blockly.Blocks.logic_LastExecute = {
  // Comparison operator.
  init: function() {
    this.setColour(120);
    this.setOutput(true, null);
    this.appendValueInput(Blockly.Msg.logic_LastExecute)
    .appendField("LastExecute:")
    .appendField(new Blockly.FieldDropdown(this.OPERATORS), 'OP');
    this.setInputsInline(true);
    var thisBlock = this;
    this.setTooltip(function() {
      var op = thisBlock.getTitleValue('OP');
      return thisBlock.TOOLTIPS[op];
    });
  }
}; 


Blockly.Blocks.logic_LastUpdate = {
  init: function() {
    this.setColour(120);
    this.setOutput(true, null);
    this.appendValueInput('A')
    .appendField(Blockly.Msg.logic_LastUpdate);
    this.appendValueInput('B')
    .appendField(new Blockly.FieldDropdown(this.OPERATORS), 'OP');    
    this.appendDummyInput();
    this.setInputsInline(true);
    this.setTooltip(Blockly.Msg.LOGIC_BOOLEAN_TOOLTIP);
  }
};


Blockly.Msg.logic_states_ON = "On";
Blockly.Msg.logic_states_OFF = "Off";
Blockly.Msg.logic_states_Open = "Open";
Blockly.Msg.logic_states_Close= "Close";
Blockly.Msg.logic_set = "Set";
Blockly.Msg.logic_setlevel = "Level (%)";
Blockly.Msg.logic_Execute = "Execute";
Blockly.Msg.logic_LastExecute = "Last Execute";
Blockly.Msg.logic_LastUpdate = "Last Update";
Blockly.Msg.For = "For";
Blockly.Msg.Minutes = "Minutes";
Blockly.Msg.Time = "Time";
Blockly.Msg.Days = "Jour";
Blockly.Msg.Temp = "Temperature";

Blockly.Blocks.logic_timeofday.OPERATORS =
[['=', 'EQ'],
['\u2260', 'NEQ'],
['<', 'LT'],
['\u2264', 'LTE'],
['>', 'GT'],
['\u2265', 'GTE']];

Blockly.Blocks.logic_LastUpdate.OPERATORS =
[['=', 'EQ'],
['\u2260', 'NEQ'],
['<', 'LT'],
['\u2264', 'LTE'],
['>', 'GT'],
['\u2265', 'GTE']];

Blockly.Blocks.logic_LastExecute.OPERATORS =
[['=', 'EQ'],
['\u2265', 'GTE']];

Blockly.Blocks.logic_weekday.DAYS =
[["Monday", '0'],
["Tuesday",'1'],
["Wednesday",'2'],
["Thursday",'3'],
["Friday",'4'],
["Saturday",'5'],
["Sunday", '6']];

Blockly.Blocks.logic_weekday.OPERATORS =
[['=', 'EQ'],
['\u2260', 'NEQ'],
['<', 'LT'],
['\u2264', 'LTE'],
['>', 'GT'],
['\u2265', 'GTE']];  

Blockly.Blocks.logic_sunrisesunset.VALUES =
[["Lever du soleil", 'Sunrise'],
["Coucher du soleil",'Sunset']];