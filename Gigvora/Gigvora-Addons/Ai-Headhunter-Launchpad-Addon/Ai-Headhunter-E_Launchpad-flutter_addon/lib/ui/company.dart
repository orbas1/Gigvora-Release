import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../state/state.dart';
import 'common.dart';

class CompanyProfileScreen extends StatefulWidget {
  final int companyId;
  final CompanyProfileState? state;
  final AnalyticsClient analytics;

  const CompanyProfileScreen({
    super.key,
    required this.companyId,
    required this.analytics,
    this.state,
  });

  @override
  State<CompanyProfileScreen> createState() => _CompanyProfileScreenState();
}

class _CompanyProfileScreenState extends State<CompanyProfileScreen> {
  CompanyProfileState get state => widget.state ?? context.read<CompanyProfileState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('CompanyProfile', {'companyId': widget.companyId});
    state.loadCompany(widget.companyId);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Company Profile')),
      body: Consumer<CompanyProfileState>(
        builder: (context, s, _) {
          if (s.loading && s.company == null) {
            return const LoadingView();
          }
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: () => state.loadCompany(widget.companyId));
          }
          final company = s.company;
          if (company == null) {
            return const EmptyView(message: 'Company not found');
          }
          return RefreshIndicator(
            onRefresh: () => state.loadCompany(widget.companyId),
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                ListTile(
                  leading: const CircleAvatar(child: Icon(Icons.business)),
                  title: Text(company.name),
                  subtitle: Text(company.description ?? ''),
                  trailing: Chip(label: Text('${company.employeeCount} employees')),
                ),
                const SizedBox(height: 12),
                if (company.jobs.isNotEmpty)
                  _BulletSection(title: 'Jobs', items: company.jobs),
                if (company.gigs.isNotEmpty)
                  _BulletSection(title: 'Gigs', items: company.gigs),
                if (company.projects.isNotEmpty)
                  _BulletSection(title: 'Projects', items: company.projects),
              ],
            ),
          );
        },
      ),
    );
  }
}

class _BulletSection extends StatelessWidget {
  final String title;
  final List<String> items;

  const _BulletSection({required this.title, required this.items});

  @override
  Widget build(BuildContext context) {
    if (items.isEmpty) return const SizedBox.shrink();
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(title, style: Theme.of(context).textTheme.titleSmall),
        ...items.map((e) => ListTile(title: Text(e))),
        const SizedBox(height: 8),
      ],
    );
  }
}
