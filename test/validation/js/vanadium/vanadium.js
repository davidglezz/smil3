Vanadium = {};
Vanadium.Version = "0.1";
Vanadium.CompatibleWithJQuery = "1.3.2";
Vanadium.Type = "jquery";
if ($().jquery.indexOf(Vanadium.CompatibleWithJQuery) != 0 && window.console && window.console.warn)
{
	console.warn("This version of Vanadium is tested with jQuery " + Vanadium.CompatibleWithJQuery + " it may not work as expected with this version (" + $().jquery + ")")
}
Vanadium.each = $.each;
Vanadium.all_elements = function ()
{
	return $("*")
};
Vanadium.partition = function (b, d)
{
	var c = [];
	var a = [];
	Vanadium.each(b, function ()
	{
		if (d(this))
		{
			c.push(this)
		}
		else
		{
			a.push(this)
		}
	});
	return [c, a]
};
var HashMap = function ()
{
	this.initialize()
};
HashMap.prototype = {
	hashkey_prefix: "<#HashMapHashkeyPerfix>",
	hashcode_field: "<#HashMapHashcodeField>",
	hashmap_instance_id: 0,
	initialize: function ()
	{
		this.backing_hash = {};
		this.code = 0;
		this.hashmap_instance_id += 1;
		this.instance_id = this.hashmap_instance_id
	},
	hashcodeField: function ()
	{
		return this.hashcode_field + this.instance_id
	},
	put: function (b, d)
	{
		var c;
		if (b && d)
		{
			var a;
			if (typeof (b) === "number" || typeof (b) === "string")
			{
				a = b
			}
			else
			{
				a = b[this.hashcodeField()]
			}
			if (a)
			{
				c = this.backing_hash[a]
			}
			else
			{
				this.code += 1;
				a = this.hashkey_prefix + this.code;
				b[this.hashcodeField()] = a
			}
			this.backing_hash[a] = [b, d]
		}
		return c === undefined ? undefined : c[1]
	},
	get: function (b)
	{
		var c;
		if (b)
		{
			var a;
			if (typeof (b) === "number" || typeof (b) === "string")
			{
				a = b
			}
			else
			{
				a = b[this.hashcodeField()]
			}
			if (a)
			{
				c = this.backing_hash[a]
			}
		}
		return c === undefined ? undefined : c[1]
	},
	del: function (b)
	{
		var d = false;
		if (b)
		{
			var a;
			if (typeof (b) === "number" || typeof (b) === "string")
			{
				a = b
			}
			else
			{
				a = b[this.hashcodeField()]
			}
			if (a)
			{
				var c = this.backing_hash[a];
				this.backing_hash[a] = undefined;
				if (c !== undefined)
				{
					b[this.hashcodeField()] = undefined;
					d = true
				}
			}
		}
		return d
	},
	each: function (c, a)
	{
		var b;
		for (b in this.backing_hash)
		{
			if (c.call(this.backing_hash[b][1], this.backing_hash[b][0], this.backing_hash[b][1]) === false)
			{
				break
			}
		}
		return this
	},
	toString: function ()
	{
		return "HashMapJS"
	}
};
Vanadium.containers = new HashMap();
var ContainerValidation = function (a)
{
	this.initialize(a)
};
ContainerValidation.prototype = {
	initialize: function (a)
	{
		this.html_element = a;
		this.elements = []
	},
	add_element: function (a)
	{
		this.elements.push(a)
	},
	decorate: function ()
	{
		var a = null;
		for (var b in this.elements)
		{
			if (this.elements[b].invalid === undefined)
			{
				a = undefined
			}
			else
			{
				if (this.elements[b].invalid === true)
				{
					a = false;
					break
				}
				else
				{
					if (this.elements[b].invalid === false && a === null)
					{
						a = true
					}
				}
			}
		}
		if (a === undefined)
		{
			$(this.html_element).removeClass(Vanadium.config.invalid_class);
			$(this.html_element).removeClass(Vanadium.config.valid_class)
		}
		else
		{
			if (a)
			{
				$(this.html_element).removeClass(Vanadium.config.invalid_class);
				$(this.html_element).addClass(Vanadium.config.valid_class)
			}
			else
			{
				$(this.html_element).removeClass(Vanadium.config.valid_class);
				$(this.html_element).addClass(Vanadium.config.invalid_class)
			}
		}
	}
};
var VanadiumForm = function (a)
{
	this.initialize(a)
};
Vanadium.forms = new HashMap();
VanadiumForm.add_element = function (b)
{
	var c = b.element.form;
	if (c)
	{
		var a = Vanadium.forms.get(c);
		if (a)
		{
			a.validation_elements.push(b)
		}
		else
		{
			a = new VanadiumForm(b);
			Vanadium.forms.put(c, a)
		}
	}
};
VanadiumForm.prototype = {
	initialize: function (a)
	{
		this.validation_elements = [a];
		this.form = a.element.form;
		var b = this;
		$(this.form).submit(function ()
		{
			var c = b.validate();
			var d = true;
			c.each(function (f, e)
			{
				for (var g in e)
				{
					if (e[g].success == false)
					{
						d = false;
						break
					}
				}
				if (d == false)
				{
					return false
				}
			});
			if (!d)
			{
				b.decorate();
				return false
			}
		});
		this.form.decorate = function ()
		{
			b.decorate()
		}
	},
	validate: function ()
	{
		var a = new HashMap();
		Vanadium.each(this.validation_elements, function ()
		{
			a.put(this, this.validate())
		});
		return a
	},
	decorate: function (a)
	{
		if (arguments.length == 0)
		{
			a = this.validate()
		}
		a.each(function (b, c)
		{
			b.decorate(c)
		})
	}
};
Vanadium.validators_types = {};
Vanadium.elements_validators_by_id = {};
Vanadium.created_advices = [];
Vanadium.config = {
	valid_class: "vanadium-valid",
	invalid_class: "vanadium-invalid",
	message_value_class: "vanadium-message-value",
	advice_class: "vanadium-advice",
	prefix: ":",
	separator: ";",
	reset_defer_timeout: 100
};
Vanadium.empty_advice_marker_class = "-vanadium-empty-advice-";
Vanadium.rules = {};
Vanadium.init = function ()
{
	this.setupValidatorTypes();
	this.scan_dom()
};
Vanadium.addValidatorType = function (c, a, b, d, e)
{
	this.validators_types[c] = new Vanadium.Type(c, a, b, d, e)
};
Vanadium.addValidatorTypes = function (b)
{
	var a = this;
	Vanadium.each(b, function ()
	{
		Vanadium.addValidatorType.apply(a, this)
	})
};
Vanadium.scan_dom = function ()
{
	Vanadium.each(Vanadium.all_elements(), function (b, d)
	{
		var c = d.className.split(" ");
		if (Vanadium.is_input_element(d))
		{
			var a = new ElementValidation(d);
			if (d.id)
			{
				Vanadium.elements_validators_by_id[d.id] = a
			}
			VanadiumForm.add_element(a);
			Vanadium.each(c, function ()
			{
				var e = Vanadium.parse_class_name(this);
				if (e)
				{
					Vanadium.add_validation_instance(a, e);
					Vanadium.add_validation_modifier(a, e)
				}
			});
			Vanadium.each(Vanadium.get_rules(d.id), function ()
			{
				var e = this;
				if (e)
				{
					Vanadium.add_validation_instance(a, e);
					Vanadium.add_validation_modifier(a, e)
				}
			});
			a.setup()
		}
		else
		{
			Vanadium.add_validation_container(d)
		}
	})
};
Vanadium.add_validation_container = function (a)
{
	var b = a.className.split(" ");
	Vanadium.each(b, function ()
	{
		var c = Vanadium.parse_class_name(this);
		if (c[0] == "container")
		{
			Vanadium.containers.put(a, new ContainerValidation(a));
			return true
		}
	});
	Vanadium.each(Vanadium.get_rules(a.id), function ()
	{
		var c = this;
		if (c == "container")
		{
			Vanadium.containers.put(a, new ContainerValidation(a));
			return true
		}
	})
};
Vanadium.get_rules = function (a)
{
	var d = function (f)
	{
		if (typeof f === "string")
		{
			return [f]
		}
		else
		{
			if (Vanadium.isArray(f))
			{
				return f
			}
			else
			{
				if (typeof (f) === "object")
				{
					return [f.validator, f.parameter, f.advice]
				}
				else
				{
					return undefined
				}
			}
		}
	};
	var e = [];
	var b = Vanadium.rules[a];
	if (typeof b === "undefined")
	{
		return []
	}
	else
	{
		if (typeof b === "string")
		{
			e.push(b)
		}
		else
		{
			if (Vanadium.isArray(b))
			{
				for (var c in b)
				{
					e.push(d(b[c]))
				}
			}
			else
			{
				if (typeof (b) === "object")
				{
					e.push(d(b))
				}
			}
		}
	}
	return e
};
Vanadium.parse_class_name = function (c)
{
	if (c.indexOf(Vanadium.config.prefix) == 0)
	{
		var a = c.substr(Vanadium.config.prefix.length).split(Vanadium.config.separator);
		for (var b in a)
		{
			if (a[b] == "")
			{
				a[b] = undefined
			}
		}
		return a
	}
	else
	{
		return []
	}
};
Vanadium.add_validation_instance = function (a, e)
{
	var d = e[0];
	var f = e[1];
	var b = e[2];
	var c = Vanadium.validators_types[d];
	if (c)
	{
		a.add_validation_instance(c, f, b)
	}
};
Vanadium.add_validation_modifier = function (a, d)
{
	var c = d[0];
	var b = d[1];
	if (c == "only_on_blur" || c == "only_on_submit" || c == "wait" || c == "advice")
	{
		a.add_validation_modifier(c, b)
	}
};
Vanadium.validate = function ()
{
	var a = new HashMap();
	Vanadium.each(this.elements_validators, function ()
	{
		a.put(this.element, this.validate())
	});
	return a
};
Vanadium.decorate = function (b)
{
	if (typeof b === "object")
	{
		if (b.toString() == "HashMapJS")
		{
			b.each(function (d, e)
			{
				d.decorate(e)
			})
		}
		else
		{
			var a;
			for (a in b)
			{
				var c = Vanadium.elements_validators_by_id[a];
				if (c)
				{
					c.decorate(b[a])
				}
			}
		}
	}
};
Vanadium.reset = function ()
{
	Vanadium.each(this.elements_validators, function ()
	{
		this.reset()
	})
};
Vanadium.isArray = function (a)
{
	return a != null && typeof a == "object" && "splice" in a && "join" in a
};
Vanadium.isFunction = function (a)
{
	return a != null && a.toString() === "[object Function]"
};
Vanadium.extend = function (c)
{
	var b = [Vanadium];
	for (var a = 0; a < arguments.length; a++)
	{
		b.push(arguments[a])
	}
	return $.extend.apply($, b)
};
Vanadium.bind = function (a, b)
{
	return function ()
	{
		return a.apply(b, arguments)
	}
};
Vanadium.extend(
{
	getElementType: function (a)
	{
		switch (true)
		{
			case (a.nodeName.toUpperCase() == "TEXTAREA"):
				return Vanadium.TEXTAREA;
			case (a.nodeName.toUpperCase() == "INPUT" && a.type.toUpperCase() == "TEXT"):
				return Vanadium.TEXT;
			case (a.nodeName.toUpperCase() == "INPUT" && a.type.toUpperCase() == "PASSWORD"):
				return Vanadium.PASSWORD;
			case (a.nodeName.toUpperCase() == "INPUT" && a.type.toUpperCase() == "CHECKBOX"):
				return Vanadium.CHECKBOX;
			case (a.nodeName.toUpperCase() == "INPUT" && a.type.toUpperCase() == "FILE"):
				return Vanadium.FILE;
			case (a.nodeName.toUpperCase() == "SELECT"):
				return Vanadium.SELECT;
			case (a.nodeName.toUpperCase() == "INPUT"):
				throw new Error("Vanadium::getElementType - Cannot use Vanadium on an " + a.type + " input!");
			default:
				throw new Error("Vanadium::getElementType - Element must be an input, select, or textarea!")
		}
	},
	is_input_element: function (a)
	{
		return (a.nodeName.toUpperCase() == "TEXTAREA") || (a.nodeName.toUpperCase() == "INPUT" && a.type.toUpperCase() == "TEXT") || (a.nodeName.toUpperCase() == "INPUT" && a.type.toUpperCase() == "PASSWORD") || (a.nodeName.toUpperCase() == "INPUT" && a.type.toUpperCase() == "CHECKBOX") || (a.nodeName.toUpperCase() == "INPUT" && a.type.toUpperCase() == "FILE") || (a.nodeName.toUpperCase() == "SELECT")
	},
	createAdvice: function (c, b, d)
	{
		var a = document.createElement("span");
		a.id = b;
		var e = document.createTextNode(d);
		a.appendChild(e);
		c.parentNode.insertBefore(a, c.nextSibling);
		this.created_advices.push(a)
	},
	addValidationClass: function (a, b)
	{
		if (a)
		{
			this.removeValidationClass(a);
			if (b)
			{
				a.className += " " + Vanadium.config.valid_class
			}
			else
			{
				a.className += " " + Vanadium.config.invalid_class
			}
		}
	},
	removeValidationClass: function (a)
	{
		if (a)
		{
			if (a.className.indexOf(Vanadium.config.invalid_class) != -1)
			{
				a.className = a.className.split(Vanadium.config.invalid_class).join(" ")
			}
			if (a.className.indexOf(Vanadium.config.valid_class) != -1)
			{
				a.className = a.className.split(Vanadium.config.valid_class).join(" ")
			}
		}
	},
	TEXTAREA: 1,
	TEXT: 2,
	PASSWORD: 3,
	CHECKBOX: 4,
	SELECT: 5,
	FILE: 6
});
ElementValidation = function (a)
{
	this.initialize(a)
};
ElementValidation.prototype = {
	initialize: function (a)
	{
		this.virgin = true;
		this.element = a;
		this.validations = [];
		this.only_on_blur = false;
		this.only_on_submit = false;
		this.wait = 100;
		this.created_advices = [];
		this.decorated = false;
		this.containers = null;
		this.invalid = undefined;
		this.advice_id = undefined
	},
	add_validation_instance: function (b, c, a)
	{
		this.validations.push(new Validation(this.element, b, c, a))
	},
	add_validation_modifier: function (a, d)
	{
		if (a == "only_on_blur")
		{
			this.only_on_blur = true
		}
		else
		{
			if (a == "only_on_submit")
			{
				this.only_on_submit = true
			}
			else
			{
				if (a == "wait")
				{
					var b = parseInt(d);
					if (b != NaN && typeof (b) === "number")
					{
						this.wait = b
					}
				}
				else
				{
					if (a == "advice")
					{
						var c = document.getElementById(d);
						if (c)
						{
							this.advice_id = d
						}
					}
				}
			}
		}
	},
	element_containers: function ()
	{
		if (this.containers === null)
		{
			this.containers = new HashMap();
			var b = this.element.parentNode;
			while (b != document)
			{
				var a = Vanadium.containers.get(b);
				if (a)
				{
					a.add_element(this);
					this.containers.put(b, a)
				}
				b = b.parentNode
			}
		}
		return this.containers
	},
	validate: function (b, c)
	{
		var a = [];
		Vanadium.each(this.validations, function ()
		{
			a.push(this.validate(b, c))
		});
		return a
	},
	decorate: function (f, d)
	{
		if (!d)
		{
			this.reset()
		}
		this.decorated = true;
		var c = this;
		var b = Vanadium.partition(f, function (g)
		{
			return g.success
		});
		var e = b[0];
		var a = b[1];
		if (a.length > 0)
		{
			this.invalid = true;
			Vanadium.addValidationClass(this.element, false)
		}
		else
		{
			if (e.length > 0 && !this.invalid)
			{
				this.invalid = false;
				Vanadium.addValidationClass(this.element, true)
			}
			else
			{
				this.invalid = undefined
			}
		}
		this.element_containers().each(function (h, g)
		{
			g.decorate()
		});
		Vanadium.each(a, function (h, j)
		{
			var i = undefined;
			if (c.advice_id)
			{
				i = document.getElementById(c.advice_id)
			}
			if (i || j.advice_id)
			{
				i = i || document.getElementById(j.advice_id);
				if (i)
				{
					$(i).addClass(Vanadium.config.advice_class);
					var g = i.childNodes.length == 0;
					if (g || $(i).hasClass(Vanadium.empty_advice_marker_class))
					{
						$(i).addClass(Vanadium.empty_advice_marker_class);
						$(i).append("<span>" + j.message + "</span>")
					}
					$(i).show()
				}
				else
				{
					i = c.create_advice(j)
				}
			}
			else
			{
				i = c.create_advice(j)
			}
			Vanadium.addValidationClass(i, false)
		})
	},
	validateAndDecorate: function ()
	{
		if (!this.virgin)
		{
			this.decorate(this.validate(this, this.decorate))
		}
	},
	create_advice: function (b)
	{
		var a = document.createElement("span");
		this.created_advices.push(a);
		$(a).addClass(Vanadium.config.advice_class);
		$(a).html(b.message);
		$(this.element).after(a);
		return a
	},
	reset: function ()
	{
		this.invalid = undefined;
		var b = document.getElementById(this.advice_id);
		if (b)
		{
			if ($(b).hasClass(Vanadium.empty_advice_marker_class))
			{
				$(b).empty()
			}
			$(b).hide()
		}
		Vanadium.each(this.validations, function ()
		{
			var c = document.getElementById(this.adviceId);
			if (c)
			{
				if ($(c).hasClass(Vanadium.empty_advice_marker_class))
				{
					$(c).empty()
				}
				$(c).hide()
			}
		});
		var a = this.created_advices.pop();
		while (!(a === undefined))
		{
			$(a).remove();
			a = this.created_advices.pop()
		}
		Vanadium.removeValidationClass(this.element)
	},
	deferValidation: function ()
	{
		if (this.wait >= 300)
		{
			this.reset()
		}
		var a = this;
		if (a.timeout)
		{
			clearTimeout(a.timeout)
		}
		a.timeout = setTimeout(function ()
		{
			$(a.element).trigger("validate")
		}, a.wait)
	},
	deferReset: function ()
	{
		var a = this;
		if (a.reset_timeout)
		{
			clearTimeout(a.reset_timeout)
		}
		a.reset_timeout = setTimeout(function ()
		{
			a.reset()
		}, Vanadium.config.reset_defer_timeout)
	},
	setup: function ()
	{
		var a = this;
		this.elementType = Vanadium.getElementType(this.element);
		this.form = this.element.form;
		this.element_containers();
		if (!this.only_on_submit)
		{
			this.observe();
			$(a.element).bind("validate", function ()
			{
				a.validateAndDecorate.call(a)
			});
			$(a.element).bind("defer_validation", function ()
			{
				a.deferValidation.call(a)
			});
			$(a.element).bind("reset", function ()
			{
				a.reset.call(a)
			})
		}
	},
	observe: function ()
	{
		var c = this.element;
		var b = Vanadium.getElementType(c);
		var a = this;
		$(c).focus(function ()
		{
			a.virgin = false
		});
		switch (b)
		{
			case Vanadium.CHECKBOX:
				$(c).click(function ()
				{
					a.virgin = false;
					$(a.element).trigger("validate")
				});
				break;
			case Vanadium.SELECT:
			case Vanadium.FILE:
				$(c).change(function ()
				{
					$(c).trigger("validate")
				});
				break;
			default:
				$(c).keydown(function (d)
				{
					if (d.keyCode != 9)
					{
						$(c).trigger("reset")
					}
				});
				if (!this.only_on_blur)
				{
					$(c).keyup(function (d)
					{
						if (d.keyCode != 9)
						{
							$(c).trigger("defer_validation")
						}
					})
				}
				$(c).blur(function ()
				{
					$(c).trigger("validate")
				})
		}
	}
};
var Validation = function (c, a, d, b)
{
	this.initialize(c, a, d, b)
};
Validation.prototype = {
	initialize: function (d, a, e, c)
	{
		this.element = d;
		this.validation_type = a;
		this.param = e;
		this.adviceId = c;
		var b = document.getElementById(c);
		if (b)
		{
			$(b).addClass(Vanadium.config.advice_class)
		}
		if (this.validation_type.init)
		{
			this.validation_type.init(this)
		}
	},
	emmit_message: function (a)
	{
		if (typeof (a) === "string")
		{
			return a
		}
		else
		{
			if (typeof (a) === "function")
			{
				return a.call(this, $(this.element).val(), this.param)
			}
		}
	},
	validMessage: function ()
	{
		return this.emmit_message(this.validation_type.validMessage()) || "ok"
	},
	invalidMessage: function ()
	{
		return this.emmit_message(this.validation_type.invalidMessage()) || "error"
	},
	test: function (a, b)
	{
		return this.validation_type.validationFunction.call(this, $(this.element).val(), this.param, this, a, b)
	},
	validate: function (b, d)
	{
		var a = {
			success: false,
			message: "Received invalid return value."
		};
		var c = this.test(b, d);
		if (typeof c === "boolean")
		{
			return {
				success: c,
				advice_id: this.adviceId,
				message: (c ? this.validMessage() : this.invalidMessage())
			}
		}
		else
		{
			if (typeof c === "object")
			{
				$.extend.apply(a, c)
			}
		}
		return a
	}
};
Vanadium.Type = function (c, a, b, d, e)
{
	this.initialize(c, a, b, d, e)
};
Vanadium.Type.prototype = {
	initialize: function (c, a, b, d, e)
	{
		this.className = c;
		this.message = d;
		this.error_message = b;
		this.validationFunction = a;
		this.init = e
	},
	test: function (a)
	{
		return this.validationFunction.call(this, a)
	},
	validMessage: function ()
	{
		return this.message
	},
	invalidMessage: function ()
	{
		return this.error_message
	},
	toString: function ()
	{
		return "className:" + this.className + " message:" + this.message + " error_message:" + this.error_message
	},
	init: function (a)
	{
		if (this.init)
		{
			this.init(a)
		}
	}
};
Vanadium.setupValidatorTypes = function ()
{
	Vanadium.addValidatorType("empty", function (a)
	{
		return ((a == null) || (a.length == 0))
	});
	Vanadium.addValidatorTypes([
		["equal", function (a, b)
		{
			return a == b
		}, function (a, b)
		{
			return 'The value should be equal to <span class="' + Vanadium.config.message_value_class + '">' + b + "</span>."
		}],
		["equal_ignore_case", function (a, b)
		{
			return a.toLowerCase() == b.toLowerCase()
		}, function (a, b)
		{
			return 'The value should be equal to <span class="' + Vanadium.config.message_value_class + '">' + b + "</span>."
		}],
		["required", function (a)
		{
			return !Vanadium.validators_types.empty.test(a)
		}, "This is a required field."],
		["accept", function (b, a, c)
		{
			return c.element.checked
		}, "Must be accepted!"],
		["integer", function (a)
		{
			if (Vanadium.validators_types.empty.test(a))
			{
				return true
			}
			var b = parseFloat(a);
			return (!isNaN(b) && b.toString() == a && Math.round(b) == b)
		}, "Please enter a valid integer in this field."],
		["number", function (a)
		{
			return Vanadium.validators_types.empty.test(a) || (!isNaN(a) && !/^\s+$/.test(a))
		}, "Please enter a valid number in this field."],
		["float", function (a)
		{
			return Vanadium.validators_types.empty.test(a) || (!isNaN(a) && !/^\s+$/.test(a))
		}, "Please enter a valid number in this field."],
		["digits", function (a)
		{
			return Vanadium.validators_types.empty.test(a) || !/[^\d]/.test(a)
		}, "Please use numbers only in this field. please avoid spaces or other characters such as dots or commas."],
		["alpha", function (a)
		{
			return Vanadium.validators_types.empty.test(a) || /^[a-zA-Z\u00C0-\u00FF\u0100-\u017E\u0391-\u03D6]+$/.test(a)
		}, "Please use letters only in this field."],
		["asciialpha", function (a)
		{
			return Vanadium.validators_types.empty.test(a) || /^[a-zA-Z]+$/.test(a)
		}, "Please use ASCII letters only (a-z) in this field."],
		["alphanum", function (a)
		{
			return Vanadium.validators_types.empty.test(a) || !/\W/.test(a)
		}, "Please use only letters (a-z) or numbers (0-9) only in this field. No spaces or other characters are allowed."],
		["date", function (a)
		{
			var b = new Date(a);
			return Vanadium.validators_types.empty.test(a) || !isNaN(b)
		}, "Please enter a valid date."],
		["email", function (a)
		{
			return (Vanadium.validators_types.empty.test(a) || /\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(a))
		}, "Please enter a valid email address. For example fred@domain.com ."],
		["url", function (a)
		{
			return Vanadium.validators_types.empty.test(a) || /^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i.test(a)
		}, "Please enter a valid URL."],
		["date_au", function (a)
		{
			if (Vanadium.validators_types.empty.test(a))
			{
				return true
			}
			var b = /^(\d{2})\/(\d{2})\/(\d{4})$/;
			if (!b.test(a))
			{
				return false
			}
			var c = new Date(a.replace(b, "$2/$1/$3"));
			return (parseInt(RegExp.$2, 10) == (1 + c.getMonth())) && (parseInt(RegExp.$1, 10) == c.getDate()) && (parseInt(RegExp.$3, 10) == c.getFullYear())
		}, "Please use this date format: dd/mm/yyyy. For example 17/03/2006 for the 17th of March, 2006."],
		["currency_dollar", function (a)
		{
			return Vanadium.validators_types.empty.test(a) || /^\$?\-?([1-9]{1}[0-9]{0,2}(\,[0-9]{3})*(\.[0-9]{0,2})?|[1-9]{1}\d*(\.[0-9]{0,2})?|0(\.[0-9]{0,2})?|(\.[0-9]{1,2})?)$/.test(a)
		}, "Please enter a valid $ amount. For example $100.00 ."],
		["selection", function (a, b)
		{
			return b.options ? b.selectedIndex > 0 : !Vanadium.validators_types.empty.test(a)
		}, "Please make a selection"],
		["one_required", function (a, c)
		{
			var b = $$('input[name="' + c.name + '"]');
			return some(b, function (d)
			{
				return getNodeAttribute(d, "value")
			})
		}, "Please select one of the above options."],
		["length", function (a, b)
		{
			if (b === undefined)
			{
				return true
			}
			else
			{
				return a.length == parseInt(b)
			}
		}, function (a, b)
		{
			return 'The value should be <span class="' + Vanadium.config.message_value_class + '">' + b + "</span> characters long."
		}],
		["min_length", function (a, b)
		{
			if (b === undefined)
			{
				return true
			}
			else
			{
				return a.length >= parseInt(b)
			}
		}, function (a, b)
		{
			return 'The value should be at least <span class="' + Vanadium.config.message_value_class + '">' + b + "</span> characters long."
		}],
		["max_length", function (a, b)
		{
			if (b === undefined)
			{
				return true
			}
			else
			{
				return a.length <= parseInt(b)
			}
		}, function (a, b)
		{
			return 'The value should be at most <span class="' + Vanadium.config.message_value_class + '">' + b + "</span> characters long."
		}],
		["same_as", function (b, c)
		{
			if (c === undefined)
			{
				return true
			}
			else
			{
				var a = document.getElementById(c);
				if (a)
				{
					return b == a.value
				}
				else
				{
					return false
				}
			}
		}, function (b, c)
		{
			var a = document.getElementById(c);
			if (a)
			{
				return 'The value should be the same as <span class="' + Vanadium.config.message_value_class + '">' + ($(a).attr("name") || a.id) + "</span> ."
			}
			else
			{
				return "There is no exemplar item!!!"
			}
		}, "", function (b)
		{
			var a = document.getElementById(b.param);
			if (a)
			{
				$(a).bind("validate", function ()
				{
					$(b.element).trigger("validate")
				})
			}
		}],
		["ajax", function (a, d, c, b, e)
		{
			if (Vanadium.validators_types.empty.test(a))
			{
				return true
			}
			if (b && e)
			{
				$.getJSON(d,
				{
					value: a,
					id: c.element.id
				}, function (f)
				{
					e.apply(b, [
						[f], true])
				})
			}
			return true
		}],
		["format", function (b, e)
		{
			var g = e.split("/");
			if (g.length == 3 && g[0] == "")
			{
				var d = g[1];
				var a = g[2];
				try
				{
					var f = new RegExp(d, a);
					return f.test(b)
				}
				catch (c)
				{
					return false
				}
			}
			else
			{
				return false
			}
		}, function (a, b)
		{
			var c = b.split("/");
			if (c.length == 3 && c[0] == "")
			{
				return 'The value should match the <span class="' + Vanadium.config.message_value_class + '">' + b.toString() + "</span> pattern."
			}
			else
			{
				return 'provided parameter <span class="' + Vanadium.config.message_value_class + '">' + b.toString() + "</span> is not valid Regexp pattern."
			}
		}]
	]);
	if (typeof (VanadiumCustomValidationTypes) !== "undefined" && VanadiumCustomValidationTypes)
	{
		Vanadium.addValidatorTypes(VanadiumCustomValidationTypes)
	}
};
$(document).ready(function ()
{
	if (typeof (VanadiumConfig) === "object" && VanadiumConfig)
	{
		Vanadium.each(VanadiumConfig, function (b, a)
		{
			Vanadium.config[b] = a
		})
	}
	if (typeof (VanadiumRules) === "object" && VanadiumRules)
	{
		Vanadium.each(VanadiumRules, function (b, a)
		{
			Vanadium.rules[b] = a
		})
	}
	Vanadium.init()
});