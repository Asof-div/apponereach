session:answer();
while (session:ready() == true) do
    session:setAutoHangup(false);
    session:set_tts_params("flite", "kal");
    session:speak("Welcome. Welcome to the VoIp World!. this is a Blind Users Programing Community. powered by Freeswitch, the free / ultimate PBX. thank to toni!");
    session:sleep(10);
    session:speak("please select an Action.");
    session:sleep(10);
    session:speak("to call the conference, press 1");
    session:sleep(10);
    session:speak("to call Freeswitch IVR, press 2");
    session:sleep(10);
    session:speak("to call Voice Mail, press 3");
    session:sleep(10);
    session:speak("for Music on hold, press 4");
    session:sleep(10);
    session:speak("to call me, press 0");
    session:sleep(30);
    digits = session:getDigits(1, "", 3000);
    if (digits == "1")  then
        session:execute("transfer","1001");
    end
    if (digits == "2")  then
        session:execute("transfer","1002");
    end
    if (digits == "3")  then
        session:execute("transfer","1003");
    end
    if (digits == "4")  then
        session:execute("transfer","1004");
    end
    if (digits == "0")  then
        session:execute("transfer","1000");
    end
end




        <referenceContainer name="sidebar.additional">
            <block class="Magento\Cms\Block\Block" name="product-sidebar">
                <arguments>
                    <argument name="block_id" xsi:type="string">product-sidebar</argument>
                </arguments>
            </block>
            <block class="Magento\LayeredNavigation\Block\Navigation\Category" name="catalog.leftnav" after="category.cms" template="Magento_LayeredNavigation::layer/view.phtml">
              <block class="Magento\LayeredNavigation\Block\Navigation\State" name="catalog.navigation.state" as="state" />
              <block class="Magento\LayeredNavigation\Block\Navigation\FilterRenderer" name="catalog.navigation.renderer" as="renderer" template="Magento_LayeredNavigation::layer/filter.phtml"/>
            </block>
        </referenceContainer>
        <referenceContainer name="sidebar.main">
            <block class="Magento\Catalog\Block\Navigation" name="catalog.leftnav" before="-" template="Magento_Catalog::navigation/left.phtml"/>
        </referenceContainer>