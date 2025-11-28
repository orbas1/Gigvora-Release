import '../models/talent_ai_models.dart';

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

List<MenuItem> talentAiMenuItems(TalentAiFeatureFlags flags) {
  final items = <MenuItem>[];
  if (flags.headhunters) {
    items.add(const MenuItem(
      id: 'headhunter-dashboard',
      label: 'Headhunters',
      routeName: '/talent-ai/headhunters',
      iconKey: 'work',
    ));
  }
  if (flags.launchpad) {
    items.add(const MenuItem(
      id: 'launchpad-programmes',
      label: 'Launchpad',
      routeName: '/talent-ai/launchpad',
      iconKey: 'school',
    ));
  }
  if (flags.aiWorkspace) {
    items.add(const MenuItem(
      id: 'ai-workspace',
      label: 'AI Workspace',
      routeName: '/talent-ai/ai-workspace',
      iconKey: 'bolt',
    ));
  }
  if (flags.volunteering) {
    items.add(const MenuItem(
      id: 'volunteering',
      label: 'Volunteering',
      routeName: '/talent-ai/volunteering',
      iconKey: 'favorite',
    ));
  }
  return items;
}
