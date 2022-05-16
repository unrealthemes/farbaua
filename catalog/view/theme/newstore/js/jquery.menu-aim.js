;(function($,window,document,undefined){var pluginName='menuAim',defaults={triggerEvent:"hover",rowSelector:"> li",handle:"> a",submenuSelector:"*",submenuDirection:"right",openClassName:"open",tolerance:75,activationDelay:0,mouseLocsTracked:3,defaultDelay:700,enterCallback:$.noop,activateCallback:$.noop,deactivateCallback:$.noop,exitCallback:$.noop,exitMenuCallback:$.noop};function Plugin(el,options){this.el=el;this.options=$.extend({},defaults,options);this._defaults=defaults;this._name=pluginName;this.init();}
Plugin.prototype={init:function(){this.activeRow=null,this.mouseLocs=[],this.lastDelayLoc=null,this.timeoutId=null,this.openDelayId=null,this.isOnClick=$.inArray(this.options.triggerEvent,['both','click'])>-1,this.isOnHover=$.inArray(this.options.triggerEvent,['both','hover'])>-1;if(this.isOnHover){this._hoverTriggerOn();}
if(this.isOnClick){this._clickTriggerOn();}},_mouseMoveDocument:function(e){obj=e.data.obj;obj.mouseLocs.push({x:e.pageX,y:e.pageY});if(obj.mouseLocs.length>obj.options.mouseLocsTracked){obj.mouseLocs.shift();}},_mouseLeaveMenu:function(e){obj=e.data.obj;if(obj.timeoutId){clearTimeout(obj.timeoutId);}
if(obj.openDelayId){clearTimeout(obj.openDelayId);}
obj._possiblyDeactivate(obj.activeRow);obj.options.exitMenuCallback(this);},_mouseEnterRow:function(e){obj=e.data.obj;if(obj.timeoutId){clearTimeout(obj.timeoutId);}
obj.options.enterCallback(this);obj._possiblyActivate(this);},_mouseLeaveRow:function(e){e.data.obj.options.exitCallback(this);},_clickRow:function(e){obj=e.data.obj;obj._activate(this);$(obj.el).find(obj.options.rowSelector).find(obj.options.handle).on('click',{obj:obj},obj._clickRowHandle);},_clickRowHandle:function(e){obj=e.data.obj;if($(this).closest('li').hasClass(obj.options.openClassName)){obj._deactivate();e.stopPropagation();}},_activate:function(row){var obj=this;if(row==this.activeRow){return;}
if(this.openDelayId){clearTimeout(this.openDelayId);}
if(parseInt(obj.options.activationDelay,0)>0&&obj.isOnHover){if(obj.activeRow){obj._activateWithoutDelay(row);}else{this.openDelayId=setTimeout(function(){obj._activateWithoutDelay(row);},obj.options.activationDelay);}}else{obj._activateWithoutDelay(row);}},_activateWithoutDelay:function(row){if(this.activeRow){this.options.deactivateCallback(this.activeRow);}
this.options.activateCallback(row);this.activeRow=row;},_deactivate:function(){if(this.openDelayId){clearTimeout(this.openDelayId);}
if(this.activeRow){this.options.deactivateCallback(this.activeRow);this.activeRow=null;}},_possiblyActivate:function(row){var delay=this._activationDelay(),that=this;if(delay){this.timeoutId=setTimeout(function(){that._possiblyActivate(row);},delay);}else{this._activate(row);}},_possiblyDeactivate:function(row){var delay=this._activationDelay(),that=this;if(delay){this.timeoutId=setTimeout(function(){that._possiblyDeactivate(row);},delay)}else{this.options.deactivateCallback(row);this.activeRow=null;}},_activationDelay:function(){if(!this.activeRow||!$(this.activeRow).is(this.options.submenuSelector)){return 0;}
var offset=$(this.el).offset(),upperLeft={x:offset.left,y:offset.top-this.options.tolerance},upperRight={x:offset.left+$(this.el).outerWidth(),y:upperLeft.y},lowerLeft={x:offset.left,y:offset.top+$(this.el).outerHeight()+this.options.tolerance},lowerRight={x:offset.left+$(this.el).outerWidth(),y:lowerLeft.y},loc=this.mouseLocs[this.mouseLocs.length-1],prevLoc=this.mouseLocs[0];if(!loc){return 0;}
if(!prevLoc){prevLoc=loc;}
if(prevLoc.x<offset.left||prevLoc.x>lowerRight.x||prevLoc.y<offset.top||prevLoc.y>lowerRight.y){return 0;}
if(this.lastDelayLoc&&loc.x==this.lastDelayLoc.x&&loc.y==this.lastDelayLoc.y){return 0;}
function slope(a,b){return(b.y-a.y)/(b.x-a.x);};var decreasingCorner=upperRight,increasingCorner=lowerRight;if(this.options.submenuDirection=="left"){decreasingCorner=lowerLeft;increasingCorner=upperLeft;}else if(this.options.submenuDirection=="below"){decreasingCorner=lowerRight;increasingCorner=lowerLeft;}else if(this.options.submenuDirection=="above"){decreasingCorner=upperLeft;increasingCorner=upperRight;}
var decreasingSlope=slope(loc,decreasingCorner),increasingSlope=slope(loc,increasingCorner),prevDecreasingSlope=slope(prevLoc,decreasingCorner),prevIncreasingSlope=slope(prevLoc,increasingCorner);if(decreasingSlope<prevDecreasingSlope&&increasingSlope>prevIncreasingSlope){this.lastDelayLoc=loc;return this.options.defaultDelay;}
this.lastDelayLoc=null;return 0;},_outsideMenuClick:function(e){var obj=e.data.obj;if($(obj.el).not(e.target)&&$(obj.el).has(e.target).length===0){obj.options.deactivateCallback(obj.activeRow);obj.activeRow=null;}},_hoverTriggerOn:function(){$(this.el).on('mouseleave',{obj:this},this._mouseLeaveMenu).find(this.options.rowSelector).on('mouseenter',{obj:this},this._mouseEnterRow).on('mouseleave',{obj:this},this._mouseLeaveRow);$(window).on('blur',{obj:this},this._mouseLeaveMenu);$(document).on('mousemove',{obj:this},this._mouseMoveDocument);},_hoverTriggerOff:function(){$(this.el).off('mouseleave',this._mouseLeaveMenu).find(this.options.rowSelector).off('mouseenter',this._mouseEnterRow).off('mouseleave',this._mouseLeaveRow)
$(window).off('blur',this._mouseLeaveMenu);$(document).off('mousemove',{obj:this},this._mouseMoveDocument);},_clickTriggerOn:function(){$(this.el).find(this.options.rowSelector).on('click',{obj:this},this._clickRow);$(document).on('click',{obj:this},this._outsideMenuClick);},_clickTriggerOff:function(){$(this.el).find(this.options.rowSelector).off('click',this._clickRow);$(document).off('click',this._outsideMenuClick);},switchToHover:function(){this._clickTriggerOff();this._hoverTriggerOn();this.isOnHover=true;this.isOnClick=false;},switchToClick:function(){this._hoverTriggerOff();this._clickTriggerOn();this.isOnHover=false;this.isOnClick=true;}};$.fn[pluginName]=function(options){var args=arguments;if(options===undefined||typeof options==='object'){return this.each(function(){if(!$.data(this,'plugin_'+pluginName)){$.data(this,'plugin_'+pluginName,new Plugin(this,options));}});}else if(typeof options==='string'&&options[0]!=='_'&&options!=='init'){var returns;this.each(function(){var instance=$.data(this,'plugin_'+pluginName);if(instance instanceof Plugin&&typeof instance[options]==='function'){returns=instance[options].apply(instance,Array.prototype.slice.call(args,1));}
if(options==='destroy'){$.data(this,'plugin_'+pluginName,null);}});return returns!==undefined?returns:this;}};}(jQuery,window,document));