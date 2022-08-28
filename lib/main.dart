import 'dart:async';
import 'dart:math';
import 'dart:typed_data';
import 'dart:ui' as ui;
import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter/rendering.dart';
import 'package:path_provider/path_provider.dart';
import 'package:scroll_to_index/scroll_to_index.dart';
import 'package:sensors_plus/sensors_plus.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Flutter Demo',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: const MyHomePage(title: 'Flutter Demo Home Page'),
    );
  }
}

class MyHomePage extends StatefulWidget {
  const MyHomePage({Key? key, required this.title}) : super(key: key);

  final String title;

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> with TickerProviderStateMixin {
  final List<String> entries = <String>['A', 'B', 'C', 'D', 'E'];
  final List<Color> colorList = <Color>[const Color(0xff00ff00), const Color(0xffff0000), const Color(0xff0000ff), const Color(0xffffff00), const Color(0xff0f0f0f)];
  GlobalKey globalKey = GlobalKey();
  List<ui.Image> image = [];
  Uint8List? bytes;
  late bool _trigger = false;
  Timer? checkTimer;

  /// ----------------------------------------------------------------------------------
  late AnimationController controller;
  late Animation<Offset> offset;
  final List<String> imgList = <String>['images/b.png', 'images/c.png', 'images/d.png'];
  final List<int> tableList = <int>[0, 4, 6, 2, 9, 1, 5, 7, 8, 3];
  final List<int> testTableList = <int>[1, 8, 7, 2, 9, 0, 5, 6, 4, 3];
  int imgCount = 0;

  /// ----------------------------------------------------------------------------------

  /// ----------------------------------------------------------------------------------
  int _currentPage = 0;
  late Timer _timer;
  final PageController _pageController = PageController(initialPage: 0);

  /// ----------------------------------------------------------------------------------

  /// ----------------------------------------------------------------------------------
  late AutoScrollController _controller;
  static const List<Color> _list = Colors.primaries;
  /// ----------------------------------------------------------------------------------

  late AnimationController _controller1;
  late ScrollController _controller2;


  /// ----------------------------------------------------------------------------------
  List<double>? _accelerometerValues;
  List<double>? _userAccelerometerValues;
  List<double>? _gyroscopeValues;
  List<double>? _magnetometerValues;
  final _streamSubscriptions = <StreamSubscription<dynamic>>[];
  /// ----------------------------------------------------------------------------------

  @override
  void initState() {
    super.initState();

    controller = AnimationController(vsync: this, duration: const Duration(milliseconds: 500));

    offset = Tween<Offset>(begin: Offset.zero, end: const Offset(1.0, 1.0)).animate(controller);

    initLoad();

    _timer = Timer.periodic(const Duration(seconds: 5), (Timer timer) {
      if (_currentPage < 2) {
        _currentPage++;
      } else {
        _currentPage = 0;
      }
      _pageController.animateToPage(_currentPage, duration: const Duration(milliseconds: 350), curve: Curves.easeIn);
    });

    _controller = AutoScrollController();

    checkTimer ??= Timer.periodic(const Duration(seconds: 1), (timer) {
      imsiTest();
    });

    _controller1 = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 2),)..repeat(reverse: true);

    _controller2 = ScrollController();
    _controller2.addListener(() {
      if (kDebugMode) {
        print(_controller2.offset);
        print('_controller2.addListener');
      }
    });
//    _controller2.animateTo(10.0, duration: const Duration(milliseconds: 500), curve: Curves.ease);

    /// -----------------------------------------------------------------
    _streamSubscriptions.add(
      accelerometerEvents.listen(
            (AccelerometerEvent event) {
          setState(() {
            _accelerometerValues = <double>[event.x, event.y, event.z];
          });
        },
      ),
    );
    _streamSubscriptions.add(
      gyroscopeEvents.listen(
            (GyroscopeEvent event) {
          setState(() {
            _gyroscopeValues = <double>[event.x, event.y, event.z];
          });
        },
      ),
    );
    _streamSubscriptions.add(
      userAccelerometerEvents.listen(
            (UserAccelerometerEvent event) {
          setState(() {
            _userAccelerometerValues = <double>[event.x, event.y, event.z];
          });
        },
      ),
    );
    _streamSubscriptions.add(
      magnetometerEvents.listen(
            (MagnetometerEvent event) {
          setState(() {
            _magnetometerValues = <double>[event.x, event.y, event.z];
          });
        },
      ),
    );
    /// ------------------------------------------------------------------------------
  }

  initLoad() {
    loadImage('images/c.png');
    loadImage('images/b.png');
    loadImage('images/d.png');
  }

  Future<void> loadImage(String path) async {
    final data = await rootBundle.load(path);
    if (kDebugMode) {
      print('data: $data');
    }
    bytes = data.buffer.asUint8List();
    var tempImg = await decodeImageFromList(bytes!);
    if (kDebugMode) {
      print('image = $tempImg');
    }
    setState(() {
      image.add(tempImg);
    });
  }

  @override
  void dispose() {
    super.dispose();
    _timer.cancel();
    _controller.dispose();
    _controller2.dispose();

    if(checkTimer != null) {
      checkTimer!.cancel();
      checkTimer = null;
    }
    _controller1.dispose();

    for (final subscription in _streamSubscriptions) {
      subscription.cancel();
    }
  }

  @override
  Widget build(BuildContext context) {
    final accelerometer =
    _accelerometerValues?.map((double v) => v.toStringAsFixed(1)).toList();
    final gyroscope =
    _gyroscopeValues?.map((double v) => v.toStringAsFixed(1)).toList();
    final userAccelerometer = _userAccelerometerValues
        ?.map((double v) => v.toStringAsFixed(1))
        .toList();
    final magnetometer =
    _magnetometerValues?.map((double v) => v.toStringAsFixed(1)).toList();


    // if (kDebugMode) {
    //   print('x = ${MediaQuery.of(context).size.width} y = ${MediaQuery.of(context).size.height}');
    // }
    return Scaffold(
      // appBar: AppBar(
      //   title: Text(widget.title),
      // ),
      body: testWheel(),//testMeters(accelerometer: accelerometer, userAccelerometer: userAccelerometer, gyroscope: gyroscope, magnetometer: magnetometer),
//          Container(
//            color: Colors.green,
//            child: Image.asset('images/a.png', width: 240, height: 240),
//          ),

      //   RepaintBoundary(
      //     key: globalKey,
      //     child: Stack(
      //       children: <Widget>[
      //         Center(child: Image.asset('images/a.png', fit: BoxFit.none)),
      //         GestureDetector(
      //           child: CustomPaint(
      //             size: Size(480, 480),
      //             painter: image.length > 0 ? MyPainter(image) : null,
      //           ),
      //         ),
      //       ],
      //     ),
      //   ),
      //
      //   Expanded(
      //     child: Container(
      //       color: Colors.green,
      //       width: MediaQuery.of(context).size.width,
      //       height: 100,
      //       child: listviewBuilder(),
      //     ),
      //   ),
      //   Container(
      //     child: TextButton(
      //       child: const Text('저장', style: TextStyle(fontWeight: FontWeight.bold)),
      //       onPressed: () {
      //         setState(() {
      //           _save();
      //         });
      //       },
      //     ),
      //   ),
    );
  }

  Widget listviewBuilder() {
    return ListView(
      scrollDirection: Axis.horizontal,
      padding: const EdgeInsets.all(5),
      children: <Widget>[
        TextButton(
          child: Text(entries[0], style: TextStyle(fontWeight: FontWeight.bold, color: colorList[0])),
          onPressed: () {},
        ),
        TextButton(
          child: Text(entries[1], style: TextStyle(fontWeight: FontWeight.bold, color: colorList[1])),
          onPressed: () {},
        ),
        TextButton(
          child: Text(entries[2], style: TextStyle(fontWeight: FontWeight.bold, color: colorList[2])),
          onPressed: () {},
        ),
        TextButton(
          child: Text(entries[3], style: TextStyle(fontWeight: FontWeight.bold, color: colorList[3])),
          onPressed: () {},
        ),
        TextButton(
          child: Text(entries[4], style: TextStyle(fontWeight: FontWeight.bold, color: colorList[4])),
          onPressed: () {},
        ),
      ],
    );
  }

  Widget testWidget() {
    return Column(
//        mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: <Widget>[
        Expanded(
          child: Container(
//              height: 100,
            color: const Color(0xffff0000),
          ),
        ),
        Container(
          height: 100,
          color: const Color(0xff0000ff),
        ),
        Container(
          height: 40,
          color: const Color(0xffffff00),
        ),
      ],
    );
  }

  Widget testSafeArea() {
    /// AppBar 없을때 영역관련
    return SafeArea(
      child: ListView.separated(
          padding: const EdgeInsets.symmetric(vertical: 10),
          itemCount: 10,
          separatorBuilder: (BuildContext context, int index) => const Divider(thickness: 3),
          itemBuilder: (BuildContext context, int index) {
            return const Text('this is some text', style: TextStyle(fontSize: 25));
          }),
    );
  }

  Widget testAni() {
    return Stack(
      children: <Widget>[
        Center(
          child: RaisedButton(
            child: const Text('Show / Hide'),
            onPressed: () {
              switch (controller.status) {
                case AnimationStatus.completed:
                  controller.reverse();
                  imgCount++;
                  if (imgCount > 2) {
                    imgCount = 0;
                  }
                  break;
                case AnimationStatus.dismissed:
                  controller.forward();
                  break;
                default:
              }
              testFunc();
              setState(() {});
            },
          ),
        ),
        Align(
          alignment: Alignment.bottomCenter,
          child: SlideTransition(
            position: offset,
            child: Padding(
              padding: const EdgeInsets.all(50.0),
              child: Image.asset(imgList[imgCount], fit: BoxFit.none),
            ),
          ),
        )
      ],
    );
  }

  void testFunc() {
    for (int value in tableList) {
      var index = tableList[value];
      var index2 = testTableList[value];
      if (index == index2) {
        if (kDebugMode) {
          print('testFunc = $index    $index2   $value');
        }
      }
    }
  }

  Widget testPageView() {
    return PageView(
      controller: _pageController,
      children: [
        Container(
          decoration: BoxDecoration(
              image: DecorationImage(
                image: AssetImage(imgList[0]),
                fit: BoxFit.cover,
              )),
        ),
        Container(
          decoration: BoxDecoration(
              image: DecorationImage(
                image: AssetImage(imgList[1]),
                fit: BoxFit.cover,
              )),
        ),
        Container(
          decoration: BoxDecoration(
              image: DecorationImage(
                image: AssetImage(imgList[2]),
                fit: BoxFit.cover,
              )),
        ),
      ],
    );
  }

  Widget testScrollIndex() {
    return ListView.builder(
        itemCount: _list.length,
        controller: _controller,
        padding: EdgeInsets.zero,
        itemBuilder: (context, index) {
          final int _random = Random().nextInt(7);
          final double _randomHeight = 100.0 * _random;
          return AutoScrollTag(
            key: ValueKey(index),
            controller: _controller,
            index: index,
            child: Container(
              width: double.infinity,
              height: _randomHeight,
              color: _list.elementAt(index),
              child: Center(
                child: Text('index: $index\nheight: ${_randomHeight.round()}'),
              ),
            ),
          );
        });
  }

  Widget testTextAni() {
    return Column(
      children: [
        Container(
          padding: EdgeInsets.zero,
          child: AnimatedDefaultTextStyle(
            child: const Text('Stitch Game!'),
            style: textStyle(),
            duration: const Duration(seconds: 1),
            curve: Curves.easeIn,
          ),
        ),
        // RaisedButton(
        //     child: const Text('button'),
        //     onPressed: () {
        //       setState(() {
        //       });
        //     }),
      ],
    );
  }

  Widget testMeters({
    required List<String>? accelerometer,
    required List<String>? userAccelerometer,
    required List<String>? gyroscope,
    required List<String>? magnetometer }) {
    return Column(
      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
      children: <Widget>[
        Padding(
          padding: const EdgeInsets.all(16.0),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: <Widget>[
              Text('Accelerometer: $accelerometer'),
            ],
          ),
        ),
        Padding(
          padding: const EdgeInsets.all(16.0),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: <Widget>[
              Text('UserAccelerometer: $userAccelerometer'),
            ],
          ),
        ),
        Padding(
          padding: const EdgeInsets.all(16.0),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: <Widget>[
              Text('Gyroscope: $gyroscope'),
            ],
          ),
        ),
        Padding(
          padding: const EdgeInsets.all(16.0),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: <Widget>[
              Text('Magnetometer: $magnetometer'),
            ],
          ),
        ),
      ],
    );
  }

  /// -----------------------------------------------------
  TextStyle textStyle() {
    return _trigger ? const TextStyle(color: Colors.blue, fontWeight: FontWeight.w900, fontSize: 20) : const TextStyle(color: Colors.black, fontWeight: FontWeight.w400, fontSize: 30);
  }

  void imsiTest() {
    _trigger = !_trigger;
    setState(() {
    });
  }

  Widget testTransition() {
    TextStyleTween _animation;
    _animation = TextStyleTween(
      begin: const TextStyle(
        color: Colors.blue,
        fontSize: 10.0,
        fontStyle: FontStyle.italic,
      ),
      end: const TextStyle(
        color: Colors.blueGrey,
        fontSize: 20.0,
        fontWeight: FontWeight.bold,
      ),
    );
    return DefaultTextStyleTransition(style: _animation.animate(_controller1), child: const Text('DefaultTextStyleTransition'));
  }
  /// --------------------------------------------------------------------------------

  Widget testWheel() {
    return SizedBox(
      height: 100,
      child: ListWheelScrollView(
        itemExtent: 30,
        diameterRatio: 1.5,
//        offAxisFraction: 0.4,
        squeeze: 0.8,
        perspective: 0.005,
        clipBehavior: Clip.antiAlias,
        physics: const FixedExtentScrollPhysics(),
//        controller: _controller2,
        children: List<Widget>.generate(10, (index) => Text('${index + 1}')),
      ),
    );
  }

  Future<void> _save() async {
    final boundary = globalKey.currentContext?.findRenderObject() as RenderRepaintBoundary?;
    final image = await boundary?.toImage();
    final directory = (await getApplicationDocumentsDirectory()).path;
    final byteData = await image?.toByteData(format: ui.ImageByteFormat.png);
    Uint8List pngBytes = byteData!.buffer.asUint8List();

    File imgFile = File('$directory/photo.png');
    await imgFile.writeAsBytes(pngBytes);
    if (kDebugMode) {
      print(imgFile);
    }
  }
}

class MyPainter extends CustomPainter {
  List<ui.Image> image;
  MyPainter(this.image);

  @override
  Future<void> paint(Canvas canvas, Size size) async {
    var paint = Paint();

    canvas.drawImage(image[0], const Offset(250, 50), paint);
    canvas.drawImage(image[1], const Offset(20, 50), paint);
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) {
    // TODO: implement shouldRepaint
    return true;
  }
}