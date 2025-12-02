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
  String _typeFilter = 'all';
  String _priceFilter = 'all';
  String _search = '';

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
    final upcoming = sessions.where((e) => e.startsAt.isAfter(DateTime.now())).toList();
    final past = sessions.where((e) => e.startsAt.isBefore(DateTime.now())).toList();
    final mine = sessions.where((e) => (e.participants).isNotEmpty).toList();
    return Scaffold(
      appBar: AppBar(
        title: const Text('Networking'),
        bottom: TabBar(
          controller: _controller,
          tabs: const [Tab(text: 'Upcoming'), Tab(text: 'My Sessions'), Tab(text: 'Past')],
        ),
      ),
      body: Column(
        children: [
          _buildFilters(context),
          Expanded(
            child: TabBarView(
              controller: _controller,
              children: [
                _buildList(_applyFilters(upcoming)),
                _buildList(_applyFilters(mine)),
                _buildList(_applyFilters(past)),
              ],
            ),
          ),
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
          meta: '${session.startsAt} • ${(session.metadata?['template'] ?? 'speed').toString().toUpperCase()}',
          trailing: InfoChip(label: session.isLive ? 'Live' : (session.isPaid ? 'Paid' : 'Free')),
          onTap: () => Navigator.pushNamed(context, '/live/networking/${session.id}', arguments: session.id),
        );
      },
    );
  }

  Widget _buildFilters(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          TextField(
            decoration: const InputDecoration(labelText: 'Search topics or host'),
            onChanged: (value) => setState(() => _search = value.toLowerCase()),
          ),
          const SizedBox(height: 8),
          Wrap(
            spacing: 8,
            children: [
              ChoiceChip(
                label: const Text('All'),
                selected: _typeFilter == 'all',
                onSelected: (_) => setState(() => _typeFilter = 'all'),
              ),
              ChoiceChip(
                label: const Text('Speed'),
                selected: _typeFilter == 'speed',
                onSelected: (_) => setState(() => _typeFilter = 'speed'),
              ),
              ChoiceChip(
                label: const Text('Group'),
                selected: _typeFilter == 'group',
                onSelected: (_) => setState(() => _typeFilter = 'group'),
              ),
              ChoiceChip(
                label: const Text('Free'),
                selected: _priceFilter == 'free',
                onSelected: (_) => setState(() => _priceFilter = 'free'),
              ),
              ChoiceChip(
                label: const Text('Paid'),
                selected: _priceFilter == 'paid',
                onSelected: (_) => setState(() => _priceFilter = 'paid'),
              ),
            ],
          ),
        ],
      ),
    );
  }

  List _applyFilters(List sessions) {
    return sessions.where((session) {
      final typeMatches = _typeFilter == 'all' || (session.metadata?['template'] ?? 'speed') == _typeFilter;
      final priceMatches = _priceFilter == 'all' || (_priceFilter == 'free' ? !session.isPaid : session.isPaid);
      final searchTarget = '${session.title} ${session.description ?? ''} ${(session.metadata?['topics'] ?? []).join(' ')}'
          .toLowerCase();
      final searchMatches = _search.isEmpty || searchTarget.contains(_search);
      return typeMatches && priceMatches && searchMatches;
    }).toList();
  }
}
