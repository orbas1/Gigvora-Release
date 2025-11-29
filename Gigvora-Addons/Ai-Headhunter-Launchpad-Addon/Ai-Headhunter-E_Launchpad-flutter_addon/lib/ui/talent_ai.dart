import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/talent_ai_analytics.dart';
import '../models/talent_ai_models.dart';
import '../state/talent_ai_state.dart';
import 'common.dart';

class HeadhunterDashboardScreen extends StatefulWidget {
  final HeadhunterState? state;
  final TalentAiAnalyticsClient analytics;

  const HeadhunterDashboardScreen({
    super.key,
    this.state,
    required this.analytics,
  });

  @override
  State<HeadhunterDashboardScreen> createState() => _HeadhunterDashboardScreenState();
}

class _HeadhunterDashboardScreenState extends State<HeadhunterDashboardScreen> {
  HeadhunterState get state => widget.state ?? context.read<HeadhunterState>();

  @override
  void initState() {
    super.initState();
    state.loadProfile();
    state.loadMandates();
    widget.analytics.trackScreen('headhunter_dashboard', {});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Headhunter Dashboard')),
      body: Consumer<HeadhunterState>(
        builder: (context, s, _) {
          if (s.loading && s.profile == null) return const LoadingView();
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: () => state.loadProfile());
          }
          return RefreshIndicator(
            onRefresh: () async {
              await state.loadProfile();
              await state.loadMandates();
            },
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                if (s.profile != null)
                  Card(
                    child: ListTile(
                      title: Text(s.profile!.headline),
                      subtitle: Text('${s.profile!.agency} • ${s.profile!.focusAreas}'),
                      trailing: Text('Mandates: ${s.profile!.mandatesCount}'),
                    ),
                  ),
                const SizedBox(height: 16),
                const SectionHeader(title: 'Active Mandates'),
                if (s.mandates.isEmpty)
                  const EmptyView(message: 'No mandates yet')
                else ...s.mandates.map(
                    (m) => Card(
                      child: ListTile(
                        title: Text(m.title),
                        subtitle: Text('${m.location} • ${m.status}'),
                        trailing: Text('${m.candidateCount} candidates'),
                        onTap: () => Navigator.of(context).push(
                          MaterialPageRoute(
                            builder: (_) => MandateDetailScreen(
                              mandateId: m.id,
                              analytics: widget.analytics,
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
              ],
            ),
          );
        },
      ),
    );
  }
}

class MandateListScreen extends StatefulWidget {
  final HeadhunterState? state;
  final TalentAiAnalyticsClient analytics;

  const MandateListScreen({super.key, this.state, required this.analytics});

  @override
  State<MandateListScreen> createState() => _MandateListScreenState();
}

class _MandateListScreenState extends State<MandateListScreen> {
  final TextEditingController _query = TextEditingController();
  HeadhunterState get state => widget.state ?? context.read<HeadhunterState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('mandate_list', {});
    state.loadMandates();
  }

  @override
  void dispose() {
    _query.dispose();
    super.dispose();
  }

  Future<void> _search() async {
    await state.loadMandates(filters: {'q': _query.text});
    widget.analytics.trackAction('mandate_search', {'q': _query.text});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Mandates')),
      body: Consumer<HeadhunterState>(
        builder: (context, s, _) {
          if (s.loading && s.mandates.isEmpty) return const LoadingView();
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: () => state.loadMandates());
          }
          return Column(
            children: [
              Padding(
                padding: const EdgeInsets.all(12),
                child: Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: _query,
                        decoration: const InputDecoration(hintText: 'Search mandates'),
                      ),
                    ),
                    IconButton(
                      onPressed: _search,
                      icon: const Icon(Icons.search),
                    ),
                  ],
                ),
              ),
              Expanded(
                child: RefreshIndicator(
                  onRefresh: state.loadMandates,
                  child: s.mandates.isEmpty
                      ? const EmptyView(message: 'No mandates found')
                      : ListView.builder(
                          itemCount: s.mandates.length,
                          itemBuilder: (context, index) {
                            final mandate = s.mandates[index];
                            return Card(
                              child: ListTile(
                                title: Text(mandate.title),
                                subtitle: Text('${mandate.location} • ${mandate.status}'),
                                trailing: Text('${mandate.candidateCount}'),
                                onTap: () => Navigator.of(context).push(
                                  MaterialPageRoute(
                                    builder: (_) => MandateDetailScreen(
                                      mandateId: mandate.id,
                                      analytics: widget.analytics,
                                    ),
                                  ),
                                ),
                              ),
                            );
                          },
                        ),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}

class MandateDetailScreen extends StatefulWidget {
  final int mandateId;
  final TalentAiAnalyticsClient analytics;
  final HeadhunterState? state;

  const MandateDetailScreen({
    super.key,
    required this.mandateId,
    required this.analytics,
    this.state,
  });

  @override
  State<MandateDetailScreen> createState() => _MandateDetailScreenState();
}

class _MandateDetailScreenState extends State<MandateDetailScreen> {
  HeadhunterState get state => widget.state ?? context.read<HeadhunterState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('mandate_detail', {'mandateId': widget.mandateId});
    state.loadMandate(widget.mandateId);
    state.loadPipeline(widget.mandateId);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Mandate Detail')),
      body: Consumer<HeadhunterState>(
        builder: (context, s, _) {
          if (s.loading && s.activeMandate == null) return const LoadingView();
          if (s.error != null) {
            return ErrorView(
              message: s.error!,
              onRetry: () {
                state.loadMandate(widget.mandateId);
                state.loadPipeline(widget.mandateId);
              },
            );
          }
          if (s.activeMandate == null) return const EmptyView(message: 'Mandate not found');
          return RefreshIndicator(
            onRefresh: () async {
              await state.loadMandate(widget.mandateId);
              await state.loadPipeline(widget.mandateId);
            },
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                ListTile(
                  title: Text(s.activeMandate!.title),
                  subtitle: Text('${s.activeMandate!.location} • ${s.activeMandate!.status}'),
                  trailing: Text('${s.activeMandate!.candidateCount} candidates'),
                ),
                const SizedBox(height: 8),
                PipelineBoard(
                  stages: s.pipelineByStage.keys.toList(),
                  groupedItems: s.pipelineByStage,
                  onMove: (itemId, stage) async {
                    await state.movePipeline(
                      mandateId: widget.mandateId,
                      itemId: itemId,
                      stage: stage,
                    );
                    widget.analytics.trackAction(
                        'pipeline_move', {'mandateId': widget.mandateId, 'stage': stage});
                  },
                  analytics: widget.analytics,
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}

class PipelineBoard extends StatelessWidget {
  final List<String> stages;
  final Map<String, List<HeadhunterPipelineItemModel>> groupedItems;
  final Future<void> Function(int itemId, String stage) onMove;
  final TalentAiAnalyticsClient analytics;

  const PipelineBoard({
    super.key,
    required this.stages,
    required this.groupedItems,
    required this.onMove,
    required this.analytics,
  });

  @override
  Widget build(BuildContext context) {
    final displayStages = stages.isEmpty ? ['sourced', 'screened', 'shortlisted', 'offer'] : stages;
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      child: Row(
        children: displayStages.map((stage) {
          final items = groupedItems[stage] ?? [];
          return SizedBox(
            width: 260,
            child: Card(
              margin: const EdgeInsets.all(8),
              child: Padding(
                padding: const EdgeInsets.all(8),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(stage.toUpperCase(), style: Theme.of(context).textTheme.titleMedium),
                    const SizedBox(height: 8),
                    if (items.isEmpty)
                      const Text('No candidates')
                    else
                      ...items.map(
                        (item) => Card(
                          child: ListTile(
                            title: Text(item.candidate.name),
                            subtitle: Text(item.candidate.email),
                            trailing: PopupMenuButton<String>(
                              onSelected: (value) => onMove(item.id, value),
                              itemBuilder: (_) => displayStages
                                  .map((s) => PopupMenuItem<String>(
                                        value: s,
                                        child: Text('Move to $s'),
                                      ))
                                  .toList(),
                            ),
                            onTap: () => Navigator.of(context).push(
                              MaterialPageRoute(
                                builder: (_) => CandidateDetailScreen(
                                  candidateId: item.candidate.id,
                                  analytics: analytics,
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),
                  ],
                ),
              ),
            ),
          );
        }).toList(),
      ),
    );
  }
}

class CandidateDetailScreen extends StatefulWidget {
  final int candidateId;
  final TalentAiAnalyticsClient analytics;
  final HeadhunterState? state;
  final AiWorkspaceState? aiState;

  const CandidateDetailScreen({
    super.key,
    required this.candidateId,
    required this.analytics,
    this.state,
    this.aiState,
  });

  @override
  State<CandidateDetailScreen> createState() => _CandidateDetailScreenState();
}

class _CandidateDetailScreenState extends State<CandidateDetailScreen> {
  late final TextEditingController _notes;
  HeadhunterState get state => widget.state ?? context.read<HeadhunterState>();
  AiWorkspaceState? get aiState {
    try {
      return widget.aiState ?? context.read<AiWorkspaceState>();
    } catch (_) {
      return widget.aiState;
    }
  }

  @override
  void initState() {
    super.initState();
    _notes = TextEditingController();
    widget.analytics.trackScreen('candidate_detail', {'candidateId': widget.candidateId});
    state.loadCandidate(widget.candidateId).then((_) {
      if (mounted && state.candidate != null) {
        _notes.text = state.candidate!.notes;
      }
    });
  }

  @override
  void dispose() {
    _notes.dispose();
    super.dispose();
  }

  Future<void> _saveNotes() async {
    await state.updateNotes(widget.candidateId, _notes.text);
    widget.analytics.trackAction('candidate_notes_updated', {'candidateId': widget.candidateId});
  }

  Future<void> _runAiSuggestions() async {
    final candidate = state.candidate;
    if (candidate == null || aiState == null) return;
    await aiState!.runTool('outreach', {
      'name': candidate.name,
      'role': candidate.stage,
      'notes': candidate.notes,
    });
    widget.analytics.trackAction('ai_outreach', {'candidateId': widget.candidateId});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Candidate Detail')),
      body: Consumer<HeadhunterState>(
        builder: (context, s, _) {
          if (s.loading && s.candidate == null) return const LoadingView();
          if (s.error != null) {
            return ErrorView(
              message: s.error!,
              onRetry: () => state.loadCandidate(widget.candidateId),
            );
          }
          final candidate = s.candidate;
          if (candidate == null) return const EmptyView(message: 'Candidate not found');
          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              ListTile(
                title: Text(candidate.name),
                subtitle: Text(candidate.email),
                trailing: Text(candidate.stage),
              ),
              const SizedBox(height: 8),
              TextField(
                controller: _notes,
                minLines: 3,
                maxLines: 5,
                decoration: const InputDecoration(labelText: 'Notes', border: OutlineInputBorder()),
              ),
              const SizedBox(height: 8),
              ElevatedButton(
                onPressed: s.loading ? null : _saveNotes,
                child: const Text('Save Notes'),
              ),
              const SizedBox(height: 12),
              if (aiState != null)
                ElevatedButton.icon(
                  onPressed: s.loading ? null : _runAiSuggestions,
                  icon: const Icon(Icons.bolt),
                  label: const Text('AI Outreach Suggestions'),
                ),
              const SizedBox(height: 16),
              const SectionHeader(title: 'Interviews'),
              if (s.interviews.isEmpty)
                const EmptyView(message: 'No interviews scheduled')
              else
                ...s.interviews.map(
                  (i) => Card(
                    child: ListTile(
                      title: Text(i.mode),
                      subtitle: Text('On ${i.scheduledAt}'),
                      trailing: Text(i.notes),
                    ),
                  ),
                ),
            ],
          );
        },
      ),
    );
  }
}

class LaunchpadProgrammeListScreen extends StatefulWidget {
  final LaunchpadState? state;
  final TalentAiAnalyticsClient analytics;

  const LaunchpadProgrammeListScreen({
    super.key,
    this.state,
    required this.analytics,
  });

  @override
  State<LaunchpadProgrammeListScreen> createState() => _LaunchpadProgrammeListScreenState();
}

class _LaunchpadProgrammeListScreenState extends State<LaunchpadProgrammeListScreen> {
  final TextEditingController _category = TextEditingController();
  LaunchpadState get state => widget.state ?? context.read<LaunchpadState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('launchpad_programmes', {});
    state.loadProgrammes();
  }

  @override
  void dispose() {
    _category.dispose();
    super.dispose();
  }

  Future<void> _applyFilters() async {
    await state.loadProgrammes(filters: {'category': _category.text});
    widget.analytics.trackAction('launchpad_filter', {'category': _category.text});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Launchpad Programmes')),
      body: Consumer<LaunchpadState>(
        builder: (context, s, _) {
          if (s.loading && s.programmes.isEmpty) return const LoadingView();
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: () => state.loadProgrammes());
          }
          return Column(
            children: [
              Padding(
                padding: const EdgeInsets.all(12),
                child: Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: _category,
                        decoration: const InputDecoration(hintText: 'Filter by category'),
                      ),
                    ),
                    IconButton(onPressed: _applyFilters, icon: const Icon(Icons.filter_alt)),
                  ],
                ),
              ),
              Expanded(
                child: RefreshIndicator(
                  onRefresh: state.loadProgrammes,
                  child: s.programmes.isEmpty
                      ? const EmptyView(message: 'No programmes available')
                      : ListView.builder(
                          itemCount: s.programmes.length,
                          itemBuilder: (context, index) {
                            final programme = s.programmes[index];
                            return Card(
                              child: ListTile(
                                title: Text(programme.title),
                                subtitle: Text(programme.category),
                                trailing: Column(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    Text('${programme.hours}h'),
                                    Text('${programme.weeks} weeks'),
                                  ],
                                ),
                                onTap: () => Navigator.of(context).push(
                                  MaterialPageRoute(
                                    builder: (_) => LaunchpadProgrammeDetailScreen(
                                      programmeId: programme.id,
                                      analytics: widget.analytics,
                                    ),
                                  ),
                                ),
                              ),
                            );
                          },
                        ),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}

class LaunchpadProgrammeDetailScreen extends StatefulWidget {
  final int programmeId;
  final LaunchpadState? state;
  final TalentAiAnalyticsClient analytics;

  const LaunchpadProgrammeDetailScreen({
    super.key,
    required this.programmeId,
    required this.analytics,
    this.state,
  });

  @override
  State<LaunchpadProgrammeDetailScreen> createState() => _LaunchpadProgrammeDetailScreenState();
}

class _LaunchpadProgrammeDetailScreenState extends State<LaunchpadProgrammeDetailScreen> {
  LaunchpadState get state => widget.state ?? context.read<LaunchpadState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('launchpad_programme_detail', {'programmeId': widget.programmeId});
    state.loadProgramme(widget.programmeId);
  }

  Future<void> _apply() async {
    await state.apply(widget.programmeId, {});
    widget.analytics.trackAction('launchpad_apply', {'programmeId': widget.programmeId});
    if (!mounted) return;
    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => LaunchpadApplicationScreen(
          applicationId: state.activeApplication?.id,
          analytics: widget.analytics,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Programme Detail')),
      body: Consumer<LaunchpadState>(
        builder: (context, s, _) {
          if (s.loading && s.selectedProgramme == null) return const LoadingView();
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: () => state.loadProgramme(widget.programmeId));
          }
          final programme = s.selectedProgramme;
          if (programme == null) return const EmptyView(message: 'Programme not found');
          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              ListTile(
                title: Text(programme.title),
                subtitle: Text(programme.category),
              ),
              Text(programme.description),
              const SizedBox(height: 8),
              Wrap(
                spacing: 8,
                children: [
                  Chip(label: Text('${programme.hours} hours')),
                  Chip(label: Text('${programme.weeks} weeks')),
                  if (programme.offersReference) const Chip(label: Text('Reference')),
                  if (programme.offersQualification)
                    const Chip(label: Text('Qualification')),
                ],
              ),
              const SizedBox(height: 12),
              const SectionHeader(title: 'Tasks'),
              if (s.tasks.isEmpty)
                const EmptyView(message: 'No tasks yet')
              else
                ...s.tasks.map((t) => CheckboxListTile(
                      value: t.completed,
                      onChanged: null,
                      title: Text(t.title),
                      subtitle: Text('${t.estimatedHours}h'),
                    )),
              const SizedBox(height: 12),
              ElevatedButton(
                onPressed: s.loading ? null : _apply,
                child: const Text('Apply'),
              ),
            ],
          );
        },
      ),
    );
  }
}

class LaunchpadApplicationScreen extends StatefulWidget {
  final int? applicationId;
  final LaunchpadState? state;
  final TalentAiAnalyticsClient analytics;

  const LaunchpadApplicationScreen({
    super.key,
    required this.applicationId,
    required this.analytics,
    this.state,
  });

  @override
  State<LaunchpadApplicationScreen> createState() => _LaunchpadApplicationScreenState();
}

class _LaunchpadApplicationScreenState extends State<LaunchpadApplicationScreen> {
  LaunchpadState get state => widget.state ?? context.read<LaunchpadState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('launchpad_application', {'applicationId': widget.applicationId});
    if (widget.applicationId != null) {
      state.loadApplication(widget.applicationId!);
    }
  }

  Future<void> _toggleTask(LaunchpadTaskModel task) async {
    final appId = state.activeApplication?.id ?? widget.applicationId;
    if (appId == null) return;
    await state.updateTask(appId, task.id, !task.completed);
    widget.analytics.trackAction('launchpad_task_toggle', {
      'applicationId': appId,
      'taskId': task.id,
      'completed': !task.completed,
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Application')),
      body: Consumer<LaunchpadState>(
        builder: (context, s, _) {
          if (s.loading && s.activeApplication == null) return const LoadingView();
          if (s.error != null) {
            return ErrorView(
                message: s.error!,
                onRetry: () {
                  if (widget.applicationId != null) {
                    state.loadApplication(widget.applicationId!);
                  }
                });
          }
          final application = s.activeApplication;
          if (application == null) return const EmptyView(message: 'Application not found');
          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              ListTile(
                title: Text('Status: ${application.status}'),
                subtitle: Text('Programme #${application.programmeId}'),
                trailing: Text(application.submittedAt?.toIso8601String() ?? ''),
              ),
              const SizedBox(height: 8),
              const SectionHeader(title: 'Tasks'),
              ...application.tasks.map(
                (t) => CheckboxListTile(
                  value: t.completed,
                  onChanged: (v) => _toggleTask(t),
                  title: Text(t.title),
                  subtitle: Text('${t.estimatedHours}h'),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}

class AiWorkspaceScreen extends StatefulWidget {
  final AiWorkspaceState? state;
  final TalentAiAnalyticsClient analytics;

  const AiWorkspaceScreen({super.key, this.state, required this.analytics});

  @override
  State<AiWorkspaceScreen> createState() => _AiWorkspaceScreenState();
}

class _AiWorkspaceScreenState extends State<AiWorkspaceScreen> {
  AiWorkspaceState get state => widget.state ?? context.read<AiWorkspaceState>();
  final TextEditingController _input = TextEditingController();
  String _tool = 'cv-writer';

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('ai_workspace', {});
    state.loadOverview();
  }

  @override
  void dispose() {
    _input.dispose();
    super.dispose();
  }

  Future<void> _runTool() async {
    await state.runTool(_tool, {'input': _input.text});
    widget.analytics.trackAction('ai_tool_run', {'tool': _tool});
  }

  @override
  Widget build(BuildContext context) {
    final tools = const [
      'cv-writer',
      'outreach',
      'calendar',
      'career-coach',
      'repurposing',
      'interview-prep',
      'images',
      'writer',
      'video',
      'marketing-bot'
    ];
    return Scaffold(
      appBar: AppBar(title: const Text('AI Workspace')),
      body: Consumer<AiWorkspaceState>(
        builder: (context, s, _) {
          if (s.loading && s.sessions.isEmpty) return const LoadingView();
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: state.loadOverview);
          }
          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              DropdownButton<String>(
                value: _tool,
                items: tools
                    .map((t) => DropdownMenuItem<String>(value: t, child: Text(t)))
                    .toList(),
                onChanged: (value) {
                  if (value != null) {
                    setState(() => _tool = value);
                  }
                },
              ),
              TextField(
                controller: _input,
                decoration: const InputDecoration(labelText: 'Prompt', border: OutlineInputBorder()),
                minLines: 3,
                maxLines: 6,
              ),
              const SizedBox(height: 8),
              ElevatedButton(
                onPressed: s.loading ? null : _runTool,
                child: const Text('Run AI'),
              ),
              const SizedBox(height: 12),
              if (s.lastSession != null)
                Card(
                  child: ListTile(
                    title: Text('Output (${s.lastSession!.tool})'),
                    subtitle: Text(s.lastSession!.output.isEmpty
                        ? 'No output yet'
                        : s.lastSession!.output),
                  ),
                ),
              const SectionHeader(title: 'Past Sessions'),
              ...s.sessions.map(
                (session) => ListTile(
                  title: Text(session.tool),
                  subtitle: Text(session.output, maxLines: 2, overflow: TextOverflow.ellipsis),
                  trailing: Text(session.createdAt.toIso8601String()),
                ),
              ),
              if (s.usage != null)
                ListTile(
                  title: const Text('Usage'),
                  subtitle: Text('Runs: ${s.usage!.totalRuns} • Tokens: ${s.usage!.tokensUsed}'),
                ),
              if (s.subscription != null && s.subscription!.plan != null)
                ListTile(
                  title: Text('Plan: ${s.subscription!.plan!.name}'),
                  subtitle: Text(
                      'Credits used ${s.subscription!.creditsUsed}/${s.subscription!.plan!.monthlyCredits}'),
                ),
            ],
          );
        },
      ),
    );
  }
}

class VolunteeringListScreen extends StatefulWidget {
  final VolunteeringState? state;
  final TalentAiAnalyticsClient analytics;

  const VolunteeringListScreen({
    super.key,
    this.state,
    required this.analytics,
  });

  @override
  State<VolunteeringListScreen> createState() => _VolunteeringListScreenState();
}

class _VolunteeringListScreenState extends State<VolunteeringListScreen> {
  final TextEditingController _query = TextEditingController();
  VolunteeringState get state => widget.state ?? context.read<VolunteeringState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('volunteering_list', {});
    state.loadOpportunities();
  }

  @override
  void dispose() {
    _query.dispose();
    super.dispose();
  }

  Future<void> _search() async {
    await state.loadOpportunities(filters: {'q': _query.text});
    widget.analytics.trackAction('volunteering_filter', {'q': _query.text});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Volunteering')),
      body: Consumer<VolunteeringState>(
        builder: (context, s, _) {
          if (s.loading && s.opportunities.isEmpty) return const LoadingView();
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: () => state.loadOpportunities());
          }
          return Column(
            children: [
              Padding(
                padding: const EdgeInsets.all(12),
                child: Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: _query,
                        decoration: const InputDecoration(hintText: 'Search opportunities'),
                      ),
                    ),
                    IconButton(onPressed: _search, icon: const Icon(Icons.search)),
                  ],
                ),
              ),
              Expanded(
                child: RefreshIndicator(
                  onRefresh: state.loadOpportunities,
                  child: s.opportunities.isEmpty
                      ? const EmptyView(message: 'No opportunities found')
                      : ListView.builder(
                          itemCount: s.opportunities.length,
                          itemBuilder: (context, index) {
                            final opp = s.opportunities[index];
                            return Card(
                              child: ListTile(
                                title: Text(opp.title),
                                subtitle: Text('${opp.organisation} • ${opp.location}${opp.remote ? ' (Remote)' : ''}'),
                                onTap: () => Navigator.of(context).push(
                                  MaterialPageRoute(
                                    builder: (_) => VolunteeringDetailScreen(
                                      opportunityId: opp.id,
                                      analytics: widget.analytics,
                                    ),
                                  ),
                                ),
                              ),
                            );
                          },
                        ),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}

class VolunteeringDetailScreen extends StatefulWidget {
  final int opportunityId;
  final VolunteeringState? state;
  final TalentAiAnalyticsClient analytics;

  const VolunteeringDetailScreen({
    super.key,
    required this.opportunityId,
    required this.analytics,
    this.state,
  });

  @override
  State<VolunteeringDetailScreen> createState() => _VolunteeringDetailScreenState();
}

class _VolunteeringDetailScreenState extends State<VolunteeringDetailScreen> {
  VolunteeringState get state => widget.state ?? context.read<VolunteeringState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('volunteering_detail', {'opportunityId': widget.opportunityId});
    state.loadOpportunity(widget.opportunityId);
  }

  Future<void> _apply() async {
    await state.apply(widget.opportunityId, {});
    widget.analytics.trackAction('volunteering_apply', {'opportunityId': widget.opportunityId});
    if (!mounted) return;
    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => VolunteeringApplicationScreen(
          applicationId: state.activeApplication?.id,
          analytics: widget.analytics,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Opportunity Detail')),
      body: Consumer<VolunteeringState>(
        builder: (context, s, _) {
          if (s.loading && s.selected == null) return const LoadingView();
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: () => state.loadOpportunity(widget.opportunityId));
          }
          final opp = s.selected;
          if (opp == null) return const EmptyView(message: 'Opportunity not found');
          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              ListTile(
                title: Text(opp.title),
                subtitle: Text('${opp.organisation} • ${opp.location}'),
                trailing: opp.remote ? const Chip(label: Text('Remote')) : null,
              ),
              const SizedBox(height: 8),
              Text(opp.description),
              const SizedBox(height: 12),
              ElevatedButton(
                onPressed: s.loading ? null : _apply,
                child: const Text('Apply'),
              ),
            ],
          );
        },
      ),
    );
  }
}

class VolunteeringApplicationScreen extends StatefulWidget {
  final int? applicationId;
  final VolunteeringState? state;
  final TalentAiAnalyticsClient analytics;

  const VolunteeringApplicationScreen({
    super.key,
    required this.applicationId,
    required this.analytics,
    this.state,
  });

  @override
  State<VolunteeringApplicationScreen> createState() => _VolunteeringApplicationScreenState();
}

class _VolunteeringApplicationScreenState extends State<VolunteeringApplicationScreen> {
  VolunteeringState get state => widget.state ?? context.read<VolunteeringState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('volunteering_application', {'applicationId': widget.applicationId});
    if (widget.applicationId != null) {
      state.loadApplication(widget.applicationId!);
    } else {
      state.loadApplications();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('My Applications')),
      body: Consumer<VolunteeringState>(
        builder: (context, s, _) {
          if (s.loading && s.activeApplication == null && s.applications.isEmpty) {
            return const LoadingView();
          }
          if (s.error != null) {
            return ErrorView(
              message: s.error!,
              onRetry: () {
                if (widget.applicationId != null) {
                  state.loadApplication(widget.applicationId!);
                } else {
                  state.loadApplications();
                }
              },
            );
          }
          final applications = s.applications;
          if (widget.applicationId != null && s.activeApplication != null) {
            return _ApplicationDetailCard(application: s.activeApplication!);
          }
          if (applications.isEmpty) return const EmptyView(message: 'No applications yet');
          return ListView(
            children: applications
                .map((a) => Card(
                      child: ListTile(
                        title: Text('Opportunity #${a.opportunityId}'),
                        subtitle: Text('Status: ${a.status}'),
                        trailing: Text(a.submittedAt?.toIso8601String() ?? ''),
                      ),
                    ))
                .toList(),
          );
        },
      ),
    );
  }
}

class _ApplicationDetailCard extends StatelessWidget {
  final VolunteeringApplicationModel application;

  const _ApplicationDetailCard({required this.application});

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        ListTile(
          title: Text('Opportunity #${application.opportunityId}'),
          subtitle: Text('Status: ${application.status}'),
          trailing: Text(application.submittedAt?.toIso8601String() ?? ''),
        ),
      ],
    );
  }
}
