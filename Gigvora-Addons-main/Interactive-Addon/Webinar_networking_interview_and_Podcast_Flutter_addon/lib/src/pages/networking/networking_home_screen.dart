import 'package:flutter/material.dart';

import '../../services/networking_service.dart';
import '../../state/networking_state.dart';
import '../../widgets/live_cards.dart';

class NetworkingHomeScreen extends StatefulWidget {
  const NetworkingHomeScreen({super.key, required this.service});

  final NetworkingService service;

  @override
  State<NetworkingHomeScreen> createState() => _NetworkingHomeScreenState();
}

class _NetworkingHomeScreenState extends State<NetworkingHomeScreen> with SingleTickerProviderStateMixin {
  late final NetworkingState _state;
  late final TabController _controller;

  @override
  void initState() {
    super.initState();
    _state = NetworkingState(widget.service)..addListener(_onState);
    _state.loadSessions();
    _controller = TabController(length: 3, vsync: this);
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
    final sessions = _state.sessions.data ?? [];
    return Scaffold(
      appBar: AppBar(
        title: const Text('Networking'),
        bottom: TabBar(
          controller: _controller,
          tabs: const [Tab(text: 'Upcoming'), Tab(text: 'My Sessions'), Tab(text: 'Past')],
        ),
      ),
      body: TabBarView(
        controller: _controller,
        children: [
          _buildList(sessions),
          _buildList(sessions.where((e) => e.isLive).toList()),
          _buildList(const []),
        ],
      ),
    );
  }

  Widget _buildList(List sessions) {
    if (sessions.isEmpty) {
      return const Center(child: Text('No sessions yet.'));
    }
    return ListView.builder(
      itemCount: sessions.length,
      itemBuilder: (context, index) {
        final session = sessions[index];
        return LiveEventCard(
          title: session.title,
          subtitle: session.host?['name']?.toString() ?? 'Host',
          meta: '${session.startsAt} • ${session.type}',
          trailing: InfoChip(label: session.isLive ? 'Live' : 'Scheduled'),
          onTap: () => Navigator.pushNamed(context, '/live/networking/${session.id}', arguments: session.id),
        );
      },
    );
  }
}
