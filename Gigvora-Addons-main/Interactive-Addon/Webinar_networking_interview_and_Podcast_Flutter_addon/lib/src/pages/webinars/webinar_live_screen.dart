import 'package:flutter/material.dart';

class WebinarLiveScreen extends StatelessWidget {
  const WebinarLiveScreen({super.key, required this.title});

  final String title;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(title)),
      body: LayoutBuilder(
        builder: (context, constraints) {
          final isLandscape = constraints.maxWidth > 600;
          return isLandscape
              ? Row(
                  children: [
                    Expanded(flex: 2, child: _videoContainer()),
                    Expanded(child: _chatPanel()),
                  ],
                )
              : Column(
                  children: [
                    Expanded(flex: 2, child: _videoContainer()),
                    Expanded(child: _chatPanel()),
                  ],
                );
        },
      ),
      bottomNavigationBar: Padding(
        padding: const EdgeInsets.all(12),
        child: Row(
          children: [
            ElevatedButton.icon(onPressed: () {}, icon: const Icon(Icons.stop), label: const Text('Stop')),
            const SizedBox(width: 8),
            OutlinedButton.icon(onPressed: () {}, icon: const Icon(Icons.fiber_manual_record), label: const Text('Record')),
            const Spacer(),
            OutlinedButton(onPressed: () => Navigator.pop(context), child: const Text('Leave')),
          ],
        ),
      ),
    );
  }

  Widget _videoContainer() {
    return Container(
      margin: const EdgeInsets.all(12),
      decoration: BoxDecoration(color: Colors.black87, borderRadius: BorderRadius.circular(12)),
      child: const Center(
        child: Text('Video Player', style: TextStyle(color: Colors.white)),
      ),
    );
  }

  Widget _chatPanel() {
    return DefaultTabController(
      length: 2,
      child: Column(
        children: [
          const TabBar(tabs: [Tab(text: 'Chat'), Tab(text: 'Q&A')]),
          Expanded(
            child: TabBarView(
              children: [
                _chatList(),
                _qaList(),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _chatList() {
    return Column(
      children: [
        Expanded(
          child: ListView(
            padding: const EdgeInsets.all(12),
            children: const [Text('Welcome to the live chat!')],
          ),
        ),
        Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            children: [
              Expanded(child: TextField(decoration: const InputDecoration(hintText: 'Type a message'))),
              const SizedBox(width: 8),
              ElevatedButton(onPressed: () {}, child: const Text('Send')),
            ],
          ),
        ),
      ],
    );
  }

  Widget _qaList() {
    return Column(
      children: [
        Expanded(
          child: ListView(padding: const EdgeInsets.all(12), children: const [Text('Submit your questions')]),
        ),
        Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            children: [
              Expanded(child: TextField(decoration: const InputDecoration(hintText: 'Ask a question'))),
              const SizedBox(width: 8),
              ElevatedButton(onPressed: () {}, child: const Text('Submit')),
            ],
          ),
        ),
      ],
    );
  }
}
