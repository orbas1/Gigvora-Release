import 'package:flutter/material.dart';

import '../../services/webinar_service.dart';
import '../../state/webinar_state.dart';
import '../../widgets/live_cards.dart';

class WebinarsHomeScreen extends StatefulWidget {
  const WebinarsHomeScreen({super.key, required this.service});

  final WebinarService service;

  @override
  State<WebinarsHomeScreen> createState() => _WebinarsHomeScreenState();
}

class _WebinarsHomeScreenState extends State<WebinarsHomeScreen> with SingleTickerProviderStateMixin {
  late final TabController _controller;
  late final WebinarState _state;

  @override
  void initState() {
    super.initState();
    _controller = TabController(length: 3, vsync: this);
    _state = WebinarState(widget.service)..addListener(_onState);
    _state.loadUpcoming();
    _state.loadAll();
  }

  void _onState() => setState(() {});

  @override
  void dispose() {
    _state.removeListener(_onState);
    _controller.dispose();
    super.dispose();
  }

  Widget _buildList(List items, {required bool isRecording}) {
    if (items.isEmpty) {
      return const Center(child: Text('Nothing here yet.'));
    }
    return ListView.builder(
      itemCount: items.length,
      itemBuilder: (context, index) {
        final item = items[index];
        return LiveEventCard(
          title: item.title,
          subtitle: item.host?['name']?.toString() ?? 'Host',
          meta: '${item.startsAt} • ${item.status}',
          trailing: isRecording ? const InfoChip(label: 'Replay') : InfoChip(label: item.isLive ? 'Live' : 'Scheduled'),
          onTap: () => Navigator.pushNamed(context, '/live/webinars/${item.id}', arguments: item.id),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Webinars'),
        bottom: TabBar(
          controller: _controller,
          tabs: const [Tab(text: 'Upcoming'), Tab(text: 'My Webinars'), Tab(text: 'Recordings')],
        ),
        actions: [
          TextButton(
            onPressed: () {},
            child: const Text('Host Webinar', style: TextStyle(color: Colors.white)),
          )
        ],
      ),
      body: TabBarView(
        controller: _controller,
        children: [
          _buildList(_state.upcoming.data ?? [], isRecording: false),
          _buildList(_state.myWebinars.data ?? [], isRecording: false),
          _buildList(_state.recordings.data ?? [], isRecording: true),
        ],
      ),
    );
  }
}
