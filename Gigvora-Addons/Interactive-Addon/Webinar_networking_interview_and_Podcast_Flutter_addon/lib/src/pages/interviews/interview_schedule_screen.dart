import 'package:flutter/material.dart';

import '../../services/interview_service.dart';
import '../../state/interview_state.dart';
import '../../widgets/live_cards.dart';

class InterviewScheduleScreen extends StatefulWidget {
  const InterviewScheduleScreen({super.key, required this.service});

  final InterviewService service;

  @override
  State<InterviewScheduleScreen> createState() => _InterviewScheduleScreenState();
}

class _InterviewScheduleScreenState extends State<InterviewScheduleScreen> with SingleTickerProviderStateMixin {
  late final InterviewState _state;
  late final TabController _controller;

  @override
  void initState() {
    super.initState();
    _controller = TabController(length: 2, vsync: this);
    _state = InterviewState(widget.service)..addListener(_onState);
    _state.loadInterviews();
  }

  void _onState() => setState(() {});

  @override
  void dispose() {
    _state.removeListener(_onState);
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final interviews = _state.interviews.data ?? [];
    return Scaffold(
      appBar: AppBar(
        title: const Text('Interviews'),
        bottom: TabBar(controller: _controller, tabs: const [Tab(text: 'Upcoming'), Tab(text: 'Past')]),
      ),
      body: TabBarView(
        controller: _controller,
        children: [
          _buildList(interviews),
          _buildList(const []),
        ],
      ),
    );
  }

  Widget _buildList(List items) {
    if (items.isEmpty) return const Center(child: Text('No interviews'));
    return ListView.builder(
      itemCount: items.length,
      itemBuilder: (context, index) {
        final item = items[index];
        return LiveEventCard(
          title: item.title,
          subtitle: item.company ?? '',
          meta: item.scheduledAt?.toString() ?? '',
          trailing: InfoChip(label: item.status ?? 'Scheduled'),
          onTap: () => Navigator.pushNamed(context, '/live/interviews/${item.id}', arguments: item.id),
        );
      },
    );
  }
}
