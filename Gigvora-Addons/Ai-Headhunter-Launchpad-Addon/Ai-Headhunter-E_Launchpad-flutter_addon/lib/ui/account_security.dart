import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../state/state.dart';
import 'common.dart';

class AccountSecurityScreen extends StatefulWidget {
  final SecurityAndVerificationState? state;
  final ProfessionalProfileState? profileState;
  final AnalyticsClient analytics;

  const AccountSecurityScreen({
    super.key,
    required this.analytics,
    this.state,
    this.profileState,
  });

  @override
  State<AccountSecurityScreen> createState() => _AccountSecurityScreenState();
}

class _AccountSecurityScreenState extends State<AccountSecurityScreen> {
  SecurityAndVerificationState get state => widget.state ?? context.read<SecurityAndVerificationState>();
  ProfessionalProfileState get profileState => widget.profileState ?? context.read<ProfessionalProfileState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('AccountSecurity', {});
    state.loadEvents({});
    state.loadAgeStatus();
    profileState.load();
  }

  Future<void> _startVerification() async {
    await state.startVerification({});
    widget.analytics.trackAction('age_verification_requested', {});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Account & Security')),
      body: Consumer2<SecurityAndVerificationState, ProfessionalProfileState>(
        builder: (context, s, p, _) {
          if (s.loading && s.events.isEmpty) return const LoadingView();
          if (s.error != null) return ErrorView(message: s.error!, onRetry: () => s.loadEvents({}));
          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              SectionHeader(title: 'Account Type'),
              SwitchListTile(
                title: const Text('Creator Mode'),
                value: p.profile?.availableForWork ?? false,
                onChanged: (val) {
                  widget.analytics.trackAction('account_type_toggled', {'creator': val});
                },
              ),
              const SizedBox(height: 12),
              SectionHeader(title: 'Security Events'),
              if (s.events.isEmpty)
                const EmptyView(message: 'No security events')
              else
                ...s.events.map(
                  (e) => ListTile(
                    leading: const Icon(Icons.shield_moon_outlined),
                    title: Text(e.event),
                    subtitle: Text(e.createdAt?.toLocal().toString() ?? ''),
                  ),
                ),
              const SizedBox(height: 12),
              SectionHeader(title: 'Age Verification'),
              ListTile(
                title: Text('Status: ${s.ageStatus?.status ?? 'Not started'}'),
                subtitle: Text(s.ageStatus?.details ?? 'Start verification to unlock restricted features'),
                trailing: ElevatedButton(
                  onPressed: s.loading ? null : _startVerification,
                  child: const Text('Start'),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}
