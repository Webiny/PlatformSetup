import BaseClass from '/Webiny/Core/Base/BaseClass';
import ComponentFactory from '/Webiny/Core/Component/ComponentFactory';
import Router from '/Webiny/Core/Router/Router';
import Registry from '/Webiny/Core/Registry';

/**
 * BaseComponent class is the main class all Webiny components should inherit from.
 * It handles construction of a valid React class, React element, etc.
 */
class BaseComponent extends BaseClass {

	constructor() {
		this.__listeners = [];
		this.__instanceId = Tools.createUID();
	}

	/**
	 * Create React component (will instantiate current Webiny component class and call 'getComponent()')
	 * NOTE: this is just a helper method
	 *
	 * @returns {*}
	 */
	static createComponent() {
		return (new this).getComponent();
	}

	/**
	 * Create react element from given component, props and inner content (children)
	 * @param component
	 * @param props
	 * @param content
	 * @returns {*}
	 */
	createElement(component, props = null, content = null){
		return React.createElement(component, props, content);
	}

	/**
	 * Get React template
	 * WARNING: DO NOT override this method!
	 * It is only used as a placeholder for Webiny HTPL parser.
	 */
	getReactTemplate() {
		// Dummy method
	}

	getInstanceId() {
		return this.__instanceId;
	}

	/**
	 * Get fully qualified component name
	 * Ex: 'Core.UI.Form'
	 * @returns {*}
	 */
	getFqn() {
		return this.getClassName();
	}

	getInitialState() {
		return {};
	}

	getDefaultProperties() {
		return {};
	}

	componentDidMount() {

	}

	componentWillReceiveProps(nextProps) {

	}

	shouldComponentUpdate() {
		return true;
	}

	componentWillUpdate(nextProps, nextState) {

	}

	componentDidUpdate(prevProps, prevState) {

	}

	componentWillMount() {

	}

	componentWillUnmount() {

	}

	/**
	 * Creates a React component
	 * @returns {*}
	 */
	getComponent() {
		return ComponentFactory.createComponent(this);
	}
}

export default BaseComponent;