class MenuItem {
  final String id;
  final String label;
  final String routeName;
  final String? iconKey;

  const MenuItem({
    required this.id,
    required this.label,
    required this.routeName,
    this.iconKey,
  });
}

const List<MenuItem> proNetworkMenuItems = [
  MenuItem(
    id: 'my-network',
    label: 'My Network',
    routeName: '/my-network',
    iconKey: 'people',
  ),
  MenuItem(
    id: 'professional-profile',
    label: 'Professional Profile',
    routeName: '/professional-profile',
    iconKey: 'badge',
  ),
  MenuItem(
    id: 'company',
    label: 'Company Pages',
    routeName: '/company',
    iconKey: 'business',
  ),
  MenuItem(
    id: 'escrow',
    label: 'Escrow & Orders',
    routeName: '/escrow',
    iconKey: 'shield',
  ),
  MenuItem(
    id: 'stories-creator',
    label: 'Stories Creator',
    routeName: '/stories-creator',
    iconKey: 'story',
  ),
  MenuItem(
    id: 'analytics',
    label: 'Analytics',
    routeName: '/analytics',
    iconKey: 'analytics',
  ),
  MenuItem(
    id: 'newsletters',
    label: 'Newsletters',
    routeName: '/newsletters',
    iconKey: 'mail',
  ),
  MenuItem(
    id: 'account-security',
    label: 'Account & Security',
    routeName: '/account-security',
    iconKey: 'lock',
  ),
];
